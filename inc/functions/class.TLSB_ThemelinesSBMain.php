<?php
if(!defined('ABSPATH')){
    exit;
   }
class TLSB_ThemelinesSBMain{
	public function __construct(){		
		tlsb_include_file( '/inc/templates/tl_social_bucket_widget.php' );
	}
	public function addAssets(){
		tlsb_include_file( '/inc/functions/class.TLSB_EnqueueAll.php' );
		if(class_exists( 'TLSB_EnqueueAll' )){
			$enqueue_file = new TLSB_EnqueueAll();
			$enqueue_file->register_hooks();
		}		
	}
	public function register(){		
		tlsb_include_file( '/inc/admin/class.TLSB_AdminMenu.php' );		
		tlsb_include_file( '/inc/functions/class.TLSB_COMMENT.php' );
	}
	public static function activate(){
		tlsb_include_file( '/inc/admin/class.TLSB_Activate.php' );
		TLSB_Activate::activate() ;
	}
	public static function deactivate(){
		tlsb_include_file( '/inc/admin/class.TLSB_Dectivate.php' );
		TLSB_Dectivate::deactivate();
	}
	public static function remove(){
		delete_option("tl_sb_settings");
		delete_option("widget_tl_sb_widget");
	}
}

if( class_exists( 'TLSB_ThemelinesSBMain' ) ){
	$tlsb_main = new TLSB_ThemelinesSBMain();
	$tlsb_main->register();
	$tlsb_main->addAssets();
}
register_activation_hook( TLSB_PLUGIN, array( 'TLSB_ThemelinesSBMain', 'activate' ) );

register_deactivation_hook( TLSB_PLUGIN, array( 'TLSB_ThemelinesSBMain', 'deactivate' ) );

register_uninstall_hook( TLSB_PLUGIN, array( 'TLSB_ThemelinesSBMain', 'remove' ) );
