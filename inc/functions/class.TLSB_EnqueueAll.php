<?php
class TLSB_EnqueueAll{
	function add_admin_style(){
		if( (is_admin() )&& ( isset( $_GET[ 'page' ] ) ) && ( $_GET[ 'page' ] == 'tl-social-bucket' ) ){
			
			//Attach all Stylesheets
			wp_enqueue_style( 'tlsb_admin_form_style', plugins_url( '/assets/css/form_style.css', dirname( dirname( __FILE__ ) ) ), false );
			
			wp_enqueue_style( 'tlsb_jquery_ui', plugins_url( '/assets/jquery-ui/jquery-ui.min.css', dirname( dirname( __FILE__ ) ) ), false );
			
			wp_enqueue_style( 'tlsb_color_picker_css', plugins_url( '/assets/color_picker/tlsb_spectrum.css', dirname( dirname( __FILE__ ) ) ), false );
			
			wp_enqueue_style( 'tlsb_admin_fontawsome-all', plugins_url( '/assets/fontawesome/css/all.min.css', dirname( dirname( __FILE__ ) ) ), false );
			
			wp_enqueue_style( 'tlsb_admin_fontawsome-all', plugins_url( '/assets/fontawesome/css/all.min.css', dirname( dirname( __FILE__ ) ) ), false );
			
		
		
		
		
			//Attach all javascript files					
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-sortable' );
						
			wp_enqueue_script( 'tlsb_color_picker_js', plugins_url( '/assets/color_picker/tlsb_spectrum.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true );
			
			wp_enqueue_script( 'tlsb_tl_cb_iconAction', plugins_url( '/assets/js/tl_sb_tl_cb_iconAction.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true );
			
			wp_enqueue_script( 'tlsb_generateSVGIcon', plugins_url( '/assets/js/tl_sb_generateSVGIcon.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true );
						
			wp_enqueue_script( 'tlsb_generateProperty', plugins_url( '/assets/js/tl_sb_generateIconProperty.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true);
			
			wp_register_script( 'tlsb_form_interface', plugins_url( '/assets/js/tl_sb_interface_action.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true);
			
			wp_enqueue_script( 'tlsb_ajax_save', plugins_url( '/assets/js/tl_social_bucket_ajax_save.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true);
			
			/*-- Google temporary */
			wp_register_script( 'wpfbr_google_places_gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDRERbmhKNYw45rS88_NY_UYztPXIhA3Iw&signed_in=true&libraries=places', array( 'jquery' ) );
			wp_enqueue_script( 'wpfbr_google_places_gmaps' );
			
			$wnm_custom  =  array( 'tlsb_template_url'	 =>	TLSB_PLUGIN_URL,
								'ajax_uri'				 =>	admin_url( 'admin-ajax.php' )
								);
			
			wp_localize_script( 'tlsb_form_interface', 'tl_sb_php_data', $wnm_custom );
			
			wp_enqueue_script( 'tlsb_form_interface' );
		
		}
	}
	public function register_hooks(){
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_frontEnd_assets' ) );
	}
	public function add_frontEnd_assets(){
		//Attach all Stylesheets
			wp_enqueue_style( 'tlsb_admin_style', plugins_url( '/assets/css/tl-sb-style.css', dirname( dirname( __FILE__ ) ) ), false );
			
			wp_enqueue_style( 'tlsb_seview_style', plugins_url( '/assets/css/review/review.css', dirname( dirname( __FILE__ ) ) ), false );	
			
		//Attach all javascript files	
			wp_enqueue_script( 'jquery' );
			
			wp_enqueue_script( 'tlsb_frontend_js', plugins_url( '/assets/js/tl_sb_action.js', dirname( dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0.0', true );
						
	}
}