<?php
/**
 * File containing the class Sensei_Background_Job_Stateful.
 *
 * @since 3.9.0
 * @package sensei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for jobs that require state.
 */
abstract class Sensei_Background_Job_Stateful implements Sensei_Background_Job_Interface {
	const NAME             = 'sensei_background_job_stateful';
	const TRANSIENT_PREFIX = 'sensei_background_job_';
	const TRANSIENT_LIFE   = DAY_IN_SECONDS;

	/**
	 * ID for the job.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Arguments for the job.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * State for the current job.
	 *
	 * @var array
	 */
	private $state;

	/**
	 * Set if the state has changed.
	 *
	 * @var bool
	 */
	private $changed = false;

	/**
	 * Set if the job has been deleted.
	 *
	 * @var bool
	 */
	private $deleted = false;

	/**
	 * Set up and enqueue job.
	 *
	 * @param array $args The job arguments.
	 */
	public static function start( $args = [] ) {
		$instance = new static( md5( uniqid() ), $args );
		Sensei_Scheduler::instance()->schedule_job( $instance );

		return $instance;
	}

	/**
	 * Sensei_Background_Job_Stateful constructor.
	 *
	 * @param string $id    The unique ID.
	 * @param array  $args  Arguments needed to run.
	 */
	private function __construct( $id, $args = [] ) {
		$this->id   = $id;
		$this->args = $args;

		$this->restore_state();
	}

	/**
	 * Get the action name for the scheduled job.
	 *
	 * @return string
	 */
	public function get_name() {
		return self::NAME;
	}

	/**
	 * Restore a job state.
	 */
	private function restore_state() {
		$state_raw = get_transient( self::TRANSIENT_PREFIX . $this->get_id() );

		$state = $state_raw ? json_decode( $state_raw ) : [];
		if ( ! is_array( $state ) ) {
			$state = [];
		}

		$this->state = $state;
	}

	/**
	 * Clean up.
	 */
	public function cleanup() {
		delete_transient( self::TRANSIENT_PREFIX . $this->get_id() );
		$this->deleted = true;
	}

	/**
	 * Get the job ID.
	 *
	 * @return string
	 */
	public function get_id() : string {
		return $this->id;
	}

	/**
	 * Get a state variable.
	 *
	 * @param mixed $default The default value.
	 *
	 * @return mixed
	 */
	public function get_state( string $key, $default = null ) {
		return $this->state[ $key ] ?? $default;
	}

	/**
	 * Update state.
	 *
	 * @param string $key   State key to update.
	 * @param mixed  $value Value to set.
	 */
	public function set_state( string $key, $value ) {
		$current_value = $this->state[ $key ] ?? null;
		if ( $current_value !== $value ) {
			$this->changed = true;
		}

		$this->state[ $key ] = $value;
	}

	/**
	 * Persist the state.
	 */
	public function persist() {
		if ( $this->deleted || ! $this->changed ) {
			return;
		}

		set_transient( self::TRANSIENT_PREFIX . $this->get_id(), wp_json_encode( $this->state ), self::TRANSIENT_LIFE );
	}

	/**
	 * Get the arguments to run with the job.
	 *
	 * @return array
	 */
	public function get_args() {
		return array_merge(
			$this->args,
			[
				'id'    => $this->id,
				'class' => static::class,
			]
		);
	}
}
