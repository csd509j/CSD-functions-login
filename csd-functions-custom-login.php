<?php
/*
Plugin Name: CSD Functions - Custom Login
Version: 1.0
Description: Login Customizations for CSD Schools and District Theme
Author: Josh Armentano
Author URI: http://abidewebdesign.com
Plugin URI: http://abidewebdesign.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require WP_CONTENT_DIR . '/plugins/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/csd509j/CSD-functions-login',
	__FILE__,
	'CSD-functions-login'
);

$myUpdateChecker->setBranch('master'); 

/*
 * Set custom login URL
 *
 * @since CSD Schools 1.0
 */
 
function csd_login_page( $login_url, $redirect, $force_reauth ) {

    return home_url( '/login/?redirect_to=' . $redirect );  
     
}
add_filter('login_url', 'csd_login_page', 10, 3);

/*
 * Set custom password reset URL
 *
 * @since CSD Schools 1.0
 */
 
function csd_password_reset($lostpassword_url, $redirect ) {
  
  return home_url('/password-reset/');

}
add_filter('lostpassword_url', 'csd_password_reset', 10, 2);

/*
 * Set custom logout redirect
 *
 * @since CSD Schools 1.0
 */
 
function csd_logout_redirect(){
  
  wp_redirect( home_url() );
  
  exit();

}
add_action('wp_logout','csd_logout_redirect');

/*
 * Set custom login failed redirect
 *
 * @since CSD Schools 1.0
 */
function csd_login_failed( $user ) {
  	
  	// check what page the login attempt is coming from
  	$referrer = $_SERVER['HTTP_REFERER'];
  	
  	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, '?login=failed' )) {
			
			// Redirect to the login page and append a querystring of login failed
	    	wp_redirect( $referrer . '?login=failed');
	    
	    } else {
	      	
	      	wp_redirect( $referrer );
	    
	    }
	    
	    exit;
	
	}
}
add_action( 'wp_login_failed', 'csd_login_failed' );