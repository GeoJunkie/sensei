<?php

namespace SenseiTest\Internal\Quiz_Submission\Submission\Repositories;

use DateTimeImmutable;
use DateTimeZone;
use Sensei\Internal\Quiz_Submission\Answer\Models\Answer;
use Sensei\Internal\Quiz_Submission\Answer\Repositories\Aggregate_Answer_Repository;
use Sensei\Internal\Quiz_Submission\Answer\Repositories\Comments_Based_Answer_Repository;
use Sensei\Internal\Quiz_Submission\Answer\Repositories\Tables_Based_Answer_Repository;
use Sensei\Internal\Quiz_Submission\Submission\Models\Submission;
use Sensei\Internal\Quiz_Submission\Submission\Repositories\Tables_Based_Submission_Repository;

/**
 * Class Aggregate_Answer_Repository_Test
 *
 * @covers \Sensei\Internal\Quiz_Submission\Answer\Repositories\Aggregate_Answer_Repository
 */
class Aggregate_Answer_Repository_Test extends \WP_UnitTestCase {
	public function testCreate_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$submission = $this->createMock( Submission::class );
		$submission->method( 'get_quiz_id' )->willReturn( 1 );
		$submission->method( 'get_user_id' )->willReturn( 2 );
		$tables_based_submission            = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_submission_repository
			->method( 'get' )
			->with( 1, 2 )
			->willReturn( $tables_based_submission );
		$repository = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'create' )
			->with( $tables_based_submission, 3, 'value' );

		$repository->create( $submission, 3, 'value' );
	}

	public function testCreate_UseTablesOn_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$submission                         = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'create' )
			->with( $submission, 1, 'value' );

		$repository->create( $submission, 1, 'value' );
	}

	public function testCreate_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$submission                         = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'create' );

		$repository->create( $submission, 1, 'value' );
	}

	public function testCreate_UseTablesOff_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$submission                         = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'create' )
			->with( $submission, 1, 'value' );

		$repository->create( $submission, 1, 'value' );
	}

	public function testGetAll_Always_CallsCommentsBasedRepository(): void {
		/* Arrange. */
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'get_all' )
			->with( 1 );

		$repository->get_all( 1 );
	}

	public function testDeleteAll_UseTablesOff_DoesntCallTablesBasedRepository(): void {
		/* Arrange. */
		$submission                         = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$tables_based
			->expects( $this->never() )
			->method( 'delete_all' );

		$repository->delete_all( $submission );
	}

	public function testDeleteAll_UseTablesOn_CallsTablesBasedRepository(): void {
		/* Arrange. */
		$submission = $this->createMock( Submission::class );
		$submission->method( 'get_quiz_id' )->willReturn( 1 );
		$submission->method( 'get_user_id' )->willReturn( 2 );
		$tables_based_submission            = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_submission_repository
			->method( 'get' )
			->with( 1, 2 )
			->willReturn( $tables_based_submission );
		$repository = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$tables_based
			->expects( $this->once() )
			->method( 'delete_all' )
			->with( $tables_based_submission );

		$repository->delete_all( $submission );
	}

	/**
	 * Test that the repository will always use comments based repository while deleting.
	 *
	 * @param bool $use_tables
	 *
	 * @dataProvider providerDeleteAll_Always_CallsCommentsBasedRepository
	 */
	public function testDeleteAll_Always_CallsCommentsBasedRepository( bool $use_tables ): void {
		/* Arrange. */
		$submission                         = $this->createMock( Submission::class );
		$comments_based                     = $this->createMock( Comments_Based_Answer_Repository::class );
		$tables_based                       = $this->createMock( Tables_Based_Answer_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$repository                         = new Aggregate_Answer_Repository(
			$comments_based,
			$tables_based,
			$tables_based_submission_repository,
			true
		);

		/* Expect & Act. */
		$comments_based
			->expects( $this->once() )
			->method( 'delete_all' )
			->with( $submission );

		$repository->delete_all( $submission );
	}

	public function providerDeleteAll_Always_CallsCommentsBasedRepository(): array {
		return [
			'uses tables'         => [ true ],
			'does not use tables' => [ false ],
		];
	}
}
