<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf(__("Hello %s"),esc_html($user_login));?></p>
<p>Thank you for creating an account with QualityAirFilters.com! We're excited to have you as part of our team. Our main mission is to make your life easier by delivering quality air filters right to your front door.</p>
<p>Our promise to you is that we will deliver your air filters to your door, exactly when you need them, every time.  We stand by our 100% satisfaction guarentee. If you are not completely satisfied with our service then you get your money back, no questions asked.</p>
<p><?php printf(__("Your account Username: <strong>%s</strong>"),esc_html( $user_login ));?></p>
<p><?php printf(__("Your account Password: <strong>%s</strong>"),esc_html( $user_pass ));?></p>
<p>You can easily check the status of your orders, adjust delivery intervals or  change your billing information by logging into your QualityAirFilters.com account at <a href='http://www.qualityairfilters.com/my-account/'>https://www.qualityairfilters.com/my-account</p>
<p>If you need any assistance  please email us at <a href='mailto:support@QualityAirFilters.com'>support@QualityAirFilters.com</a> or call us at 866-865-2603.</p>
<p>The QualityAirFilters.com Team</p>

<?php do_action( 'woocommerce_email_footer' ); ?>