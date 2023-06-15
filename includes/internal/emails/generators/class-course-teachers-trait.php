<?php
/**
 * File containing the Post_Type class.
 *
 * @package sensei
 */

namespace Sensei\Internal\Emails\Generators;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Course_Teachers_Triat.
 *
 * @internal
 *
 * @since $$next-version$$
 */
trait Course_Teachers_Triat {
	/**
	 * Get the teacher IDs for a given course.
	 *
	 * @internal
	 *
	 * @since $$next-version$$
	 *
	 * @param int $course_id The course ID.
	 * @return array The teacher IDs.
	 */
	public function get_course_teachers( $course_id ): array {
		$teacher_id = get_post_field( 'post_author', $course_id, 'raw' );

		/**
		 * Filter the teacher IDs for a given course.
		 *
		 * @since $$next-version$$
		 *
		 * @param array $teacher_ids The teacher IDs.
		 * @param int   $course_id   The course ID.
		 * @return array The teacher IDs.
		 */
		return apply_filters( 'sensei_email_course_teachers', array( $teacher_id ), $course_id );
	}
}
