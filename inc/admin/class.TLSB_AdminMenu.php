<?php
class TLSB_AdminMenu{
	public function __construct(){
		add_action('admin_menu', array( $this, 'add_tl_sb_menu_page'));
		add_filter( 'plugin_action_links_'.TLSB_PLUGIN, array($this, 'settings_link'));
	}
	public function settings_link($links){
		$settings_lin='<a href="'. esc_url( "admin.php?page=tl-social-bucket&action=addNew" ) .'">Settings</a>';
		array_push($links, $settings_lin);
		return $links;
	}
	public function add_tl_sb_menu_page(){
		$icon = TLSB_PLUGIN_URL.'assets/images/social-media.png';
		add_menu_page('Themelines Plugin', 'Social Bucket', 'manage_options', 'tl-social-bucket', array( $this, 'tl_sb_admin_menu_cb'), $icon, 110);
		add_action('admin_init', array($this, 'create_custom_settings'));
	}
	public function create_custom_settings(){
		register_setting('tl_sb_setting_group', 'tl_sb_settings');
		add_settings_section('tl_sb_settings_section', '', array($this, 'tl_sb_section_settings_cb'), 'tl-social-bucket');		
	}
	public function tl_sb_admin_menu_cb(){
		echo '<div class="tl-social_bucket-title"><h1>Social Bucket</h1></div>';
		tlsb_include_file('/inc/templates/tl_sb_admin_display.php');
	}
	function tl_sb_section_settings_cb(){
		
		tlsb_include_file('/inc/templates/tl_sb_form.php');		
	}
}
if(class_exists('TLSB_AdminMenu')){
	$tl_sb_obj= new TLSB_AdminMenu();
}

function tl_sb_shortCode($id, $key){
	$tl_sb_scObj	=	new TLSB_SC();
	$tl_sbSc		=	sanitize_text_field($tl_sb_scObj->generateShortcode($id, $key));
	return $tl_sbSc;
}
add_shortcode('tl-sb', array('TLSB_SC', 'generateContext'));