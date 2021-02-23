/**
 * External dependencies
 */
import { keyBy, uniq } from 'lodash';

/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import questionTypesConfig from '../../answer-blocks';

/**
 * Questions for selection.
 *
 * @param {Object}   props
 * @param {Array}    props.questionCategories     Question categories.
 * @param {Object}   props.filters                Filters object.
 * @param {number[]} props.selectedQuestionIds    Seleted question IDs.
 * @param {Object}   props.setSelectedQuestionIds Seleted question IDs state setter.
 */
const Questions = ( {
	questionCategories,
	filters,
	selectedQuestionIds,
	setSelectedQuestionIds,
} ) => {
	const questions = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'postType', 'question', {
				per_page: 100,
				...filters,
			} ),
		[ filters ]
	);

	const questionCategoriesById = keyBy( questionCategories, 'id' );

	const toggleAllHandler = ( checked ) => {
		const questionIds = questions.map( ( question ) => question.id );

		setSelectedQuestionIds( ( prev ) =>
			checked
				? uniq( [ ...prev, ...questionIds ] )
				: prev.filter(
						( question ) => ! questionIds.includes( question )
				  )
		);
	};

	const toggleQuestion = ( questionId ) => ( checked ) => {
		if ( checked ) {
			setSelectedQuestionIds( ( prev ) => [ ...prev, questionId ] );
		} else {
			setSelectedQuestionIds( ( prev ) =>
				prev.filter( ( id ) => id !== questionId )
			);
		}
	};

	const questionsMap = ( question ) => {
		const type =
			questionTypesConfig[ question[ 'question-type-slug' ] ]?.title;

		const categories = question[ 'question-category' ]
			.map(
				( questionCategoryId ) =>
					questionCategoriesById[ questionCategoryId ]?.name
			)
			.join( ', ' );

		return (
			<tr key={ question.id }>
				<td className="sensei-lms-quiz-block__questions-modal__question-checkbox">
					<CheckboxControl
						id={ `question-${ question.id }` }
						checked={ selectedQuestionIds.includes( question.id ) }
						onChange={ toggleQuestion( question.id ) }
					/>
				</td>
				<td className="sensei-lms-quiz-block__questions-modal__question-title">
					<label htmlFor={ `question-${ question.id }` }>
						{ question.title.rendered }
					</label>
				</td>
				<td>{ type }</td>
				<td>{ categories }</td>
			</tr>
		);
	};

	return (
		<div className="sensei-lms-quiz-block__questions-modal__questions">
			{ questions && questions.length === 0 && (
				<p>{ __( 'No questions found.', 'sensei-lms' ) }</p>
			) }
			{ questions && questions.length > 0 && (
				<table className="sensei-lms-quiz-block__questions-modal__table">
					<thead>
						<tr>
							<th>
								<CheckboxControl
									title={ __(
										'Toggle all visible questions selection.',
										'sensei-lms'
									) }
									checked={ questions.every( ( question ) =>
										selectedQuestionIds.includes(
											question.id
										)
									) }
									onChange={ toggleAllHandler }
								/>
							</th>
							<th>{ __( 'Question', 'sensei-lms' ) }</th>
							<th>{ __( 'Type', 'sensei-lms' ) }</th>
							<th>{ __( 'Category', 'sensei-lms' ) }</th>
						</tr>
					</thead>
					<tbody>{ questions.map( questionsMap ) }</tbody>
				</table>
			) }
		</div>
	);
};

export default Questions;
