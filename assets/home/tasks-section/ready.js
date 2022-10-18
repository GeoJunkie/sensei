/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Icon } from '@wordpress/components';
import { closeSmall } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import CheckIcon from '../../icons/checked.svg';
import FacebookCircleIcon from '../../icons/facebook-circle.svg';
import InstagramCircleIcon from '../../icons/instagram-circle.svg';
import TwitterCircleIcon from '../../icons/twitter-circle.svg';

/**
 * Tasks ready component.
 *
 * @param {Object}   props           Component props.
 * @param {Function} props.onDismiss Dismiss callback.
 */
const Ready = ( { onDismiss } ) => {
	const dismissTasks = () => {
		onDismiss();

		const formData = new window.FormData();
		formData.append( '_wpnonce', window.sensei_home.dismiss_tasks_nonce );
		formData.append( 'action', 'sensei_home_tasks_dismiss' );

		window.fetch( window.ajaxurl, {
			method: 'POST',
			body: formData,
		} );
	};

	return (
		<div role="alert" className="sensei-home-ready">
			<button
				className="sensei-home-ready__dismiss"
				title={ __( 'Dismiss tasks', 'sensei-lms' ) }
				onClick={ dismissTasks }
			>
				<Icon icon={ closeSmall } />
			</button>

			<div className="sensei-home-ready__check-icon">
				<CheckIcon />
			</div>

			<p className="sensei-home-ready__text">
				{ __(
					'Your new course is ready to meet its students! Share it with the world.',
					'sensei-lms'
				) }
			</p>

			<ul className="sensei-home-ready__social-links">
				<li>
					<a
						className="sensei-home-ready__social-link"
						href="https://TODO"
					>
						<FacebookCircleIcon />
						<span className="screen-reader-text">
							{ __( 'Facebook', 'sensei-lms' ) }
						</span>
					</a>
				</li>
				<li>
					<a
						className="sensei-home-ready__social-link"
						href="https://TODO"
					>
						<InstagramCircleIcon />
						<span className="screen-reader-text">
							{ __( 'Instagram', 'sensei-lms' ) }
						</span>
					</a>
				</li>
				<li>
					<a
						className="sensei-home-ready__social-link"
						href="https://TODO"
					>
						<TwitterCircleIcon />
						<span className="screen-reader-text">
							{ __( 'Twitter', 'sensei-lms' ) }
						</span>
					</a>
				</li>
			</ul>
		</div>
	);
};

export default Ready;
