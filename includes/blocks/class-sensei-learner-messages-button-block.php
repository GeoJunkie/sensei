<?php
/**
 * File containing the Sensei_Learner_Messages_Button_Block class.
 *
 * @package sensei
 * @since 3.10.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block for Learner Messages button.
 */
class Sensei_Learner_Messages_Button_Block {

	/**
	 * Sensei_Learner_Messages_Button_Block constructor.
	 */
	public function __construct() {
		$this->register_block();
	}


	/**
	 * Register learner messages block.
	 *
	 * @access private
	 */
	public function register_block() {
		Sensei_Blocks::register_sensei_block(
			'sensei-lms/button-learner-messages',
			[ 'render_callback' => [ $this, 'render_learner_messages_block' ] ]
		);
	}

	/**
	 * Render the learner messages button.
	 *
	 * @access private
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block HTML.
	 *
	 * @return string Link to the learner messages button.
	 */
	public function render_learner_messages_block( $attributes, $content ): string {
		if ( Sensei()->settings->settings['messages_disable'] ?? false ) {
			return '';
		}

		$message_url = esc_url( get_post_type_archive_link( 'sensei_message' ) );

		return preg_replace(
			'/<a /',
			'<a href="' . $message_url . '" ',
			$content,
			1
		);
	}
}
