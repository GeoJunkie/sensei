<?php
/**
 * Student Message Reply email pattern.
 *
 * @package sensei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","right":"56px","bottom":"56px","left":"56px"}},"color":{"background":"#ffffff"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#ffffff;padding-top:40px;padding-right:56px;padding-bottom:56px;padding-left:56px"><!-- wp:post-title {"style":{"typography":{"textTransform":"none","fontStyle":"normal","fontWeight":"700","fontSize":"40px"},"color":{"text":"#101517"}}} /-->

	<!-- wp:group {"style":{"color":{"background":"#f6f7f7"},"spacing":{"padding":{"top":"32px","right":"32px","bottom":"32px","left":"32px"},"margin":{"top":"48px"}}}} -->
	<div class="wp-block-group has-background" style="background-color:#f6f7f7;margin-top:48px;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:paragraph {"style":{"typography":{"fontSize":"32px"},"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"},"margin":{"top":"0px","right":"0px","bottom":"24px","left":"0px"}},"color":{"text":"#101517"}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:0px;margin-right:0px;margin-bottom:24px;margin-left:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:32px"><strong>[teacher:displayname]</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"},"spacing":{"margin":{"top":"0px","bottom":"0px"},"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}},"color":{"text":"#101517"}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:16px"><strong>Course Name</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"},"color":{"text":"#101517"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:0;margin-bottom:0;font-size:16px">[course:name]</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"},"spacing":{"margin":{"top":"24px","bottom":"0px"},"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}},"color":{"text":"#101517"}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:24px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;font-size:16px"><strong>Message Content</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"},"color":{"text":"#101517"},"spacing":{"margin":{"top":"0","bottom":"24px"}}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:0;margin-bottom:24px;font-size:16px">[subject:displaysubject]</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"style":{"typography":{"fontSize":"16px"},"color":{"text":"#101517"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
		<p class="has-text-color" style="color:#101517;margin-top:0;margin-bottom:0;font-size:16px">[message:displaymessage]</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"40px","bottom":"0px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:40px;margin-bottom:0px"><!-- wp:button {"style":{"spacing":{"padding":{"top":"16px","bottom":"16px","left":"20px","right":"20px"}},"color":{"background":"#101517","text":"#fefefe"},"typography":{"fontSize":"16px"}}} -->
			<div class="wp-block-button has-custom-font-size" style="font-size:16px"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="[reply:url]" style="color:#fefefe;background-color:#101517;padding-top:16px;padding-right:20px;padding-bottom:16px;padding-left:20px">Reply Here</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

		<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1","fontSize":"16px"},"spacing":{"padding":{"top":"0px"},"margin":{"top":"24px"}}}} -->
		<p style="margin-top:24px;padding-top:0px;font-size:16px;line-height:1">*You can also reply directly to the sender by email, but the response will not be logged by the course.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
