<?php
/**
* Plugin Name: Social Bucket
* Description: This is used to create social link, share etc.
* Plugin URI: www.themelines.com
* Author: Themelines
* Author URI: https://codecloudweb.com
* version:2.0.2
* Lisence:GPLv2
* Text Domain:social-bucket
* Copyright: © 2019 Themelines / Social Bucket.
*/
if(!defined('ABSPATH')){
    exit;
   }
define( 'TLSB_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'TLSB_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'TLSB_PLUGIN', plugin_basename(__FILE__) );

if(!function_exists('tlsb_include_file')):
	function tlsb_include_file($filename){
		if(file_exists(dirname(__FILE__).$filename)){
			require_once(dirname(__FILE__).$filename);
		}
	}
endif;
tlsb_include_file( '/inc/functions/tlsbComons.php' );
tlsb_include_file( '/inc/functions/class.TLSB_ThemelinesSBMain.php' );
tlsb_include_file( '/inc/functions/interfce.servicetype.php' );
tlsb_include_file( '/inc/functions/tlsb-ajax.php' );
tlsb_include_file( '/inc/templates/tl_sb_follow.php' );
tlsb_include_file( '/inc/templates/tl_sb_review.php' );
tlsb_include_file( '/inc/templates/tl_sb_share.php' );
tlsb_include_file( '/inc/templates/tl_sb_comment.php' );
tlsb_include_file( '/inc/admin/class.TLSB_SC.php' );
tlsb_include_file( '/inc/functions/class.TLSB_SHARE.php' );
