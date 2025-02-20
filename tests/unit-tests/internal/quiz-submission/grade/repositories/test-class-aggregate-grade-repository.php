<?php

namespace SenseiTest\Internal\Quiz_Submission\Grade\Repositories;

use DateTimeImmutable;
use Sensei\Internal\Quiz_Submission\Answer\Repositories\Tables_Based_Answer_Repository;
use Sensei\Internal\Quiz_Submission\Grade\Models\Grade;
use Sensei\Internal\Quiz_Submission\Grade\Repositories\Aggregate_Grade_Repository;
use Sensei\Internal\Quiz_Submission\Grade\Repositories\Comments_Based_Grade_Repository;
use Sensei\Internal\Quiz_Submission\Grade\Repositories\Tables_Based_Grade_Repository;
use Sensei\Internal\Quiz_Submission\Submission\Models\Submission;
use Sensei\Internal\Quiz_Submission\Submission\Repositories\Tables_Based_Submission_Repository;

/**
 * Tests for Aggregate_Grade_Repository class.
 *
 * @covers \Sensei\Internal\Quiz_Submission\Grade\Repositories\Aggregate_Grade_Repository
 */
class Aggregate_Grade_Repository_Test extends \WP_UnitTestCase {
	public function testCreate_Always_UsesCommentsBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$comments_based_repository
			->expects( $this->once() )
			->method( 'create' )
			->with( $submission, 2, 3, 4, 'feedback' );
		$repository->create( $submission, 2, 3, 4, 'feedback' );
	}

	public function testCreate_UseTablesSetToFalse_DoesntUseCommentsBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->never() )
			->method( 'create' );
		$repository->create( $submission, 2, 3, 4, 'feedback' );
	}

	public function testGetAll_Always_UsesCommentsBasedRepository(): void {
		/* Arrange */
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$comments_based_repository
			->expects( $this->once() )
			->method( 'get_all' )
			->with( 1 );
		$repository->get_all( 1 );
	}

	public function testGetAll_Always_DoesntUseTablesBasedRepository(): void {
		/* Arrange */
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->never() )
			->method( 'get_all' );
		$repository->get_all( 1 );
	}

	public function testDeleteAll_Always_UsesCommentsBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$comments_based_repository
			->expects( $this->once() )
			->method( 'delete_all' )
			->with( $submission );
		$repository->delete_all( $submission );
	}

	public function testDeleteAll_UseTablesSetToFalse_DoesntUseTablesBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			false
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->never() )
			->method( 'delete_all' );
		$repository->delete_all( $submission );
	}

	public function testDeleteAll_UseTablesSetToTrue_UsesTablesBasedRepository(): void {
		/* Arrange */
		$submission = $this->createMock( Submission::class );
		$submission->method( 'get_quiz_id' )->willReturn( 1 );
		$submission->method( 'get_user_id' )->willReturn( 2 );

		$tables_based_submission = $this->createMock( Submission::class );

		$comments_based_repository = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository   = $this->createMock( Tables_Based_Grade_Repository::class );

		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_submission_repository
			->method( 'get' )
			->with( 1, 2 )
			->willReturn( $tables_based_submission );

		$tables_based_answer_repository = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->once() )
			->method( 'delete_all' )
			->with( $tables_based_submission );
		$repository->delete_all( $submission );
	}

	public function testSaveMany_Always_UsesCommentsBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );
		$grades                             = [ $this->createMock( Grade::class ) ];

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$comments_based_repository
			->expects( $this->once() )
			->method( 'save_many' )
			->with( $submission, $grades );
		$repository->save_many( $submission, $grades );
	}

	public function testSaveMany_UseTablesSetToFalse_DoesntUseCommentsBasedRepository(): void {
		/* Arrange */
		$submission                         = $this->createMock( Submission::class );
		$comments_based_repository          = $this->createMock( Comments_Based_Grade_Repository::class );
		$tables_based_repository            = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_answer_repository     = $this->createMock( Tables_Based_Answer_Repository::class );
		$grades                             = [ $this->createMock( Grade::class ) ];

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->never() )
			->method( 'get_all' );
		$repository->save_many( $submission, $grades );
	}

	public function testSaveMany_UseTablesSetToTrue_UsesCommentsBasedRepository(): void {
		/* Arrange */
		$submission = $this->createMock( Submission::class );
		$submission->method( 'get_quiz_id' )->willReturn( 5 );
		$submission->method( 'get_user_id' )->willReturn( 6 );

		$comments_based_repository = $this->createMock( Comments_Based_Grade_Repository::class );

		$existing_grade          = new Grade( 1, 2, 3, 4, 'feedback', new DateTimeImmutable(), new DateTimeImmutable() );
		$tables_based_repository = $this->createMock( Tables_Based_Grade_Repository::class );
		$tables_based_repository
			->method( 'get_all' )
			->with( 8 )
			->willReturn( [ $existing_grade ] );

		$grades = [ new Grade( 1, 2, 3, 4, 'feedback2', new DateTimeImmutable(), new DateTimeImmutable() ) ];

		$tables_based_submission = $this->createMock( Submission::class );
		$tables_based_submission->method( 'get_id' )->willReturn( 8 );

		$tables_based_submission_repository = $this->createMock( Tables_Based_Submission_Repository::class );
		$tables_based_submission_repository
			->method( 'get' )
			->with( 5, 6 )
			->willReturn( $tables_based_submission );

		$tables_based_answer_repository = $this->createMock( Tables_Based_Answer_Repository::class );

		$repository = new Aggregate_Grade_Repository(
			$comments_based_repository,
			$tables_based_repository,
			$tables_based_submission_repository,
			$tables_based_answer_repository,
			true
		);

		/* Expect & Act */
		$tables_based_repository
			->expects( $this->once() )
			->method( 'save_many' )
			->with(
				$tables_based_submission,
				$this->callback(
					function ( array $grades ) {
						$this->assertSame( 1, count( $grades ) );
						$this->assertSame( 'feedback2', $grades[0]->get_feedback() );

						return true;
					}
				)
			);
		$repository->save_many( $submission, $grades );
	}
}
