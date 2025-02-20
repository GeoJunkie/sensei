<?php

namespace SenseiTest\Internal\Student_Progress\Course_Progress\Repositories;

use Sensei\Internal\Student_Progress\Course_Progress\Models\Course_Progress;
use Sensei\Internal\Student_Progress\Course_Progress\Repositories\Aggregate_Course_Progress_Repository;
use Sensei\Internal\Student_Progress\Course_Progress\Repositories\Comments_Based_Course_Progress_Repository;
use Sensei\Internal\Student_Progress\Course_Progress\Repositories\Tables_Based_Course_Progress_Repository;

class Aggregate_Course_Progress_Repository_Test extends \WP_UnitTestCase {
	public function testCreate_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'create' )
			->with( 1, 2 );
		$repository->create( 1, 2 );
	}

	public function testCreate_UseTablesOn_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'create' )
			->with( 1, 2 );
		$repository->create( 1, 2 );
	}

	public function testCreate_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'create' );
		$repository->create( 1, 2 );
	}

	public function testCreate_UseTablesOff_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'create' )
			->with( 1, 2 );
		$repository->create( 1, 2 );
	}

	public function testGet_Always_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'get' )
			->with( 1, 2 );
		$repository->get( 1, 2 );
	}

	public function testHas_Always_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'has' )
			->with( 1, 2 );
		$repository->has( 1, 2 );
	}

	/**
	 * Test that the repository will always use comments based repository while saving.
	 *
	 * @param bool $use_tables
	 * @dataProvider providerSave_Always_CallsCommentsBasedRepository
	 */
	public function testSave_Always_CallsCommentsBasedRepository( bool $use_tables ): void {
		/* Arrange. */
		$progress       = $this->create_course_progress();
		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$repository     = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, $use_tables );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'save' )
			->with( $progress );
		$repository->save( $progress );
	}

	public function providerSave_Always_CallsCommentsBasedRepository(): array {
		return [
			'uses tables'         => [ true ],
			'does not use tables' => [ false ],
		];
	}

	public function testSave_UseTablesOnAndProgressFound_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$progress       = $this->create_course_progress();
		$found_progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$tables_based
			->method( 'get' )
			->with( 2, 3 )
			->willReturn( $found_progress );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'save' )
			->with(
				$this->callback(
					function ( Course_Progress $progress_to_save ) use ( $progress, $found_progress ) {
						self::assertNotSame( $progress, $progress_to_save, 'We should create a new progress based on a found one: not using passed for saving.' );
						self::assertNotSame( $found_progress, $progress_to_save, 'We should create a new progress based on a found one: not the found one itself.' );
						return true;
					}
				)
			);
		$repository->save( $progress );
	}

	public function testSave_UseTablesOnAndProgressFound_ConvertsTimeToUtc(): void {
		/* Arrange. */
		$progress = $this->create_course_progress( new \DateTimeImmutable( '2020-01-01 03:00:00', new \DateTimeZone( 'GMT+03:00' ) ) );

		$found_progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$tables_based
			->method( 'get' )
			->with( 2, 3 )
			->willReturn( $found_progress );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'save' )
			->with(
				$this->callback(
					function ( Course_Progress $progress_to_save ) {
						return '2020-01-01 00:00:00' === $progress_to_save->get_started_at()->format( 'Y-m-d H:i:s' );
					}
				)
			);
		$repository->save( $progress );
	}

	public function testSave_UseTablesOnAndProgressNotFound_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );
		$tables_based
			->method( 'get' )
			->with( 2, 3 )
			->willReturn( null );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'save' );
		$repository->save( $progress );
	}

	public function testDelete_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'delete' );
		$repository->delete( $progress );
	}

	public function testDelete_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'delete' )
			->with( $progress );
		$repository->delete( $progress );
	}

	/**
	 * Test that the repository will always use comments based repository while deleting.
	 *
	 * @param bool $use_tables
	 *
	 * @dataProvider providerDelete_Always_CallsCommentsBasedRepository
	 */
	public function testDelete_Always_CallsCommentsBasedRepository( $use_tables ): void {
		/* Arrange. */
		$progress = $this->create_course_progress();

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, $use_tables );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'delete' );
		$repository->delete( $progress );
	}

	public function providerDelete_Always_CallsCommentsBasedRepository(): array {
		return [
			'uses tables'         => [ true ],
			'does not use tables' => [ false ],
		];
	}

	public function testDeleteForCourse_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$course_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'delete_for_course' );
		$repository->delete_for_course( $course_id );
	}

	public function testDeleteForCourse_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$course_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'delete_for_course' )
			->with( $course_id );
		$repository->delete_for_course( $course_id );
	}

	/**
	 * Test that the repository will always use comments based repository while deleting.
	 *
	 * @param bool $use_tables
	 *
	 * @dataProvider providerDeleteForCourse_Always_CallsCommentsBasedRepository
	 */
	public function testDeleteForCourse_Always_CallsCommentsBasedRepository( $use_tables ): void {
		/* Arrange. */
		$course_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, $use_tables );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'delete_for_course' );
		$repository->delete_for_course( $course_id );
	}

	public function providerDeleteForCourse_Always_CallsCommentsBasedRepository(): array {
		return [
			'uses tables'         => [ true ],
			'does not use tables' => [ false ],
		];
	}

	public function testDeleteForUser_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$user_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, false );

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'delete_for_user' );
		$repository->delete_for_user( $user_id );
	}

	public function testDeleteForUser_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$user_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, true );

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'delete_for_user' )
			->with( $user_id );
		$repository->delete_for_user( $user_id );
	}

	/**
	 * Test that the repository will always use comments based repository while deleting.
	 *
	 * @param bool $use_tables
	 *
	 * @dataProvider providerDeleteForUser_Always_CallsCommentsBasedRepository
	 */
	public function testDeleteForUser_Always_CallsCommentsBasedRepository( $use_tables ): void {
		/* Arrange. */
		$user_id = 2;

		$comments_based = $this->createMock( Comments_Based_Course_Progress_Repository::class );
		$tables_based   = $this->createMock( Tables_Based_Course_Progress_Repository::class );

		$repository = new Aggregate_Course_Progress_Repository( $comments_based, $tables_based, $use_tables );

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'delete_for_user' );
		$repository->delete_for_user( $user_id );
	}

	public function providerDeleteForUser_Always_CallsCommentsBasedRepository(): array {
		return [
			'uses tables'         => [ true ],
			'does not use tables' => [ false ],
		];
	}

	/**
	 * Creates a course progress object.
	 *
	 * @param \DateTimeInterface|null $started_at Started at date.
	 * @return Course_Progress
	 */
	public function create_course_progress( $started_at = null ): Course_Progress {
		return new Course_Progress(
			1,
			2,
			3,
			'in-progress',
			$started_at ?? new \DateTimeImmutable(),
			new \DateTimeImmutable(),
			new \DateTimeImmutable(),
			new \DateTimeImmutable()
		);
	}
}
