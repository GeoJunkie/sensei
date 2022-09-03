<?php
/**
 * File containing the Grade_Comments_Repository class.
 *
 * @package sensei
 */

namespace Sensei\Quiz_Submission\Grade\Repositories;

use Sensei\Quiz_Submission\Grade\Models\Grade;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Grade_Comments_Repository.
 *
 * @since $$next-version$$
 */
class Grade_Comments_Repository implements Grade_Repository_Interface {
	/**
	 * Creates a new grade.
	 *
	 * @param int         $submission_id The submission ID.
	 * @param int         $answer_id     The answer ID.
	 * @param int         $question_id   The question ID.
	 * @param int         $points        The points.
	 * @param string|null $feedback      The feedback.
	 *
	 * @return Grade The grade.
	 */
	public function create( int $submission_id, int $answer_id, int $question_id, int $points, string $feedback = null ): Grade {
		$grades_map                 = get_comment_meta( $submission_id, 'quiz_grades', true );
		$grades_map                 = is_array( $grades_map ) ? $grades_map : [];
		$grades_map[ $question_id ] = $points;

		update_comment_meta( $submission_id, 'quiz_grades', $grades_map );

		if ( $feedback ) {
			$feedback_map                 = get_comment_meta( $submission_id, 'quiz_answers_feedback', true );
			$feedback_map                 = is_array( $feedback_map ) ? $feedback_map : [];
			$feedback_map[ $question_id ] = $feedback;

			update_comment_meta( $submission_id, 'quiz_answers_feedback', $feedback_map );
		}

		return new Grade( 0, $answer_id, $question_id, $points, $feedback, current_datetime() );
	}

	/**
	 * Get all grades for a quiz submission.
	 *
	 * @param int $submission_id The submission ID.
	 *
	 * @return Grade[] An array of grades.
	 */
	public function get_all( int $submission_id ): array {
		$grades_map = get_comment_meta( $submission_id, 'quiz_grades', true );

		if ( ! $grades_map || ! is_array( $grades_map ) ) {
			return [];
		}

		$feedback_map = get_comment_meta( $submission_id, 'quiz_answers_feedback', true );

		$grades = [];
		foreach ( $grades_map as $question_id => $points ) {
			$feedback = $feedback_map[ $question_id ] ?? null;
			$grades[] = new Grade( 0, 0, $question_id, $points, $feedback, current_datetime() );
		}

		return $grades;
	}

	/**
	 * Save multiple grades.
	 *
	 * @param Grade[] $grades        An array of grades.
	 * @param int     $submission_id The submission ID.
	 */
	public function save_many( array $grades, int $submission_id ): void {
		$grades_map   = [];
		$feedback_map = [];

		foreach ( $grades as $grade ) {
			$grades_map[ $grade->get_question_id() ]   = $grade->get_points();
			$feedback_map[ $grade->get_question_id() ] = $grade->get_feedback();
		}

		update_comment_meta( $submission_id, 'quiz_grades', $grades_map );
		update_comment_meta( $submission_id, 'quiz_answers_feedback', $feedback_map );
	}

	/**
	 * Delete all grades for a submission.
	 *
	 * @param int $submission_id The submission ID.
	 */
	public function delete_all( int $submission_id ): void {
		delete_comment_meta( $submission_id, 'quiz_grades' );
		delete_comment_meta( $submission_id, 'quiz_answers_feedback' );
	}
}
