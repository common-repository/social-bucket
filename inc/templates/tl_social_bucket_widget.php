<?php
if(!defined('ABSPATH')){
	exit;
 }
function tl_social_bucket_widget() {
    register_widget( 'tl_sb_widget' );
}
add_action( 'widgets_init', 'tl_social_bucket_widget' );

class tl_sb_widget extends WP_Widget {
	private $tlsb_review_script='';
	private $tl_sb_array;
	function __construct() {
		parent::__construct(		
		'tl_sb_widget', 
		
		// Widget name will appear in UI
		__('Social Bucket', 'themeline-social-bucket'), 
		
		// Widget description
		array( 'description'  => __( 'Add social Button in your widget', 'themeline-social-bucket' ), ));
		$this->tl_sb_array =  get_option( 'tl_sb_settings');
	}
	
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title  =  apply_filters( 'widget_title', $instance[ 'title' ] );
		$align  =  isset( $instance[ 'align' ] ) ? ('style=text-align:'. $instance[ 'align' ] ) .'' : 'style=text-align:left' ;
		
		// before and after widget arguments are defined by themes
		echo $args[ 'before_widget' ];
		if ( ! empty( $title ) )
		echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		
		// This is where you run the code and display the output
		
		
		$instanceFrom			 =  $instance[ 'tl_sb_follow_id' ];
		$splitArray				 =  explode( '.', $instanceFrom );
		$social_array			 =  isset($this->tl_sb_array[ $splitArray[ 0 ] ][ $splitArray[ 1 ] ] ) ? $this->tl_sb_array[ $splitArray[ 0 ] ][ $splitArray[ 1 ] ] : '';

	?>
	<div class = "tl-social-bucket">
		<?php
		if( $splitArray[ 0 ] == 'share' ){
			if( isset( $instance[ 'tl_sb_follow_id' ]) && !empty( $social_array ) ):
			$preview_area_content = '';
			//add_action( 'wp_head', array( $this, 'generateMeta' ) ) ;
			$sticky_status = isset( $social_array[ 'settings' ][ 'isSticky' ] ) && ( $social_array[ 'settings' ][ 'isSticky' ] ) == 'Checked' ? 'Checked' : '';
			$icon_link = isset( $social_array[ 'settings' ][ 'link_open_opt' ] ) ? $social_array[ 'settings' ][ 'link_open_opt' ] : '_self';
			$padding = isset( $social_array[ 'settings' ][ 'padding' ] ) ? 'style = "padding:'.$social_array[ 'settings' ][ 'padding' ].'px"' : '';
			$preview_area_content.= '<div class = "tl-sb-preview-area tl-sb-stickypos-inline" '. $align .'>';
			$uri = urlencode( get_home_url() );
			if( isset( $social_array[ 'settings' ][ 'ordering' ] ) && count( $social_array[ 'settings' ][ 'ordering' ] )>0 ){					
				foreach( $social_array[ 'settings' ][ 'ordering' ] as $iconkey ){
					$arr[ 'iconDefault' ] = isset( $social_array[ 'icons' ][ $iconkey ][ 'color' ][ 'bydefault' ] ) ? $social_array[ 'icons' ][ $iconkey ][ 'color' ][ 'bydefault' ] : '#fff';
					$arr[ 'iconHover' ] = isset( $social_array[ 'icons' ][ $iconkey ][ 'color' ][ 'hover' ]) ? $social_array[ 'icons' ][ $iconkey ][ 'color' ][ 'hover' ] : '#fff';
					$arr[ 'bgDefault' ] = isset( $social_array[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'bydefault' ] ) ? $social_array[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'bydefault' ] : tlsb_generateColor($iconkey, 'default');
					$arr[ 'bgHover' ] = isset( $social_array[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'hover' ] ) ? $social_array[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'hover' ] : tlsb_generateColor($iconkey, 'hover');
					$url = esc_url(tlsb_getUrlContent( $iconkey, $uri ) );
					$preview_area_content .= '<div class="tl-sb-icon-head" data-name="'. $iconkey .'"><div class="tl-sb-icon-wrapper"><a href="'.$url.'" target="'.$icon_link.'" '.$padding.'>'.stripslashes_deep($social_array['icons'][$iconkey]['content']).'</a><div class="tlsb-tooltip">'. $iconkey .'</div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div></div>';		
					}
				}
			$preview_area_content.= '</div>';					
			echo $preview_area_content;
			endif ;
		}else if( $splitArray[ 0 ] == 'review' ){
			if( isset( $instance[ 'tl_sb_follow_id' ]) && !empty( $social_array ) ):
			$preview_area_content = '';
			$sticky_status = isset( $social_array[ 'settings' ][ 'isSticky' ]) && ( $social_array[ 'settings' ][ 'isSticky' ] ) == 'Checked' ? 'Checked' : '';
			$padding = isset( $social_array[ 'settings' ][ 'padding' ] ) ? 'style = "padding:'.$social_array[ 'settings' ][ 'padding' ].'px"' : '';
			$preview_area_content.= '<div class = "tl-sb-preview-area tl-sb-stickypos-inline" '. $align .'>';
			if(isset($social_array['settings']['ordering'])){
				foreach($social_array['settings']['ordering'] as $icon){
					$arr['iconDefault']=isset($social_array['icons'][$icon]['color']['bydefault'])?$social_array['icons'][$icon]['color']['bydefault']:'#fff';
					$arr['iconHover']=isset($social_array['icons'][$icon]['color']['hover'])?$social_array['icons'][$icon]['color']['hover']:'#fff';
					$arr['bgDefault']=isset($social_array['icons'][$icon]['bgcolor']['bydefault'])?$social_array['icons'][$icon]['bgcolor']['bydefault']:tlsb_generateColor($icon, 'default');
					$arr['bgHover']=isset($social_array['icons'][$icon]['bgcolor']['hover'])?$social_array['icons'][$icon]['bgcolor']['hover']:tlsb_generateColor($icon, 'hover');
					$preview_area_content .= '<div class="tl-sb-icon-head" data-name="'. $icon .'"><div class="tl-sb-icon-wrapper"><a href="#" target="_self" '.$padding.'>'.stripslashes_deep($social_array['icons'][$icon]['content']).'</a>';
					
					/* ---- Rating Stars ---- */
					$rating = isset($social_array['icons'][$icon]['user_rating'])?$social_array['icons'][$icon]['user_rating']:'';					
					
					
					
					$preview_area_content .= '<div class="tlsb-tooltip">'. $icon .'</div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div>';
					
					if($icon == 'googlemybusiness') {
						$rating = isset($social_array[ 'settings' ][ 'addStar' ]) && $social_array[ 'settings' ][ 'addStar' ] == 'Checked'? 'rating':'';
						$review = isset($social_array[ 'settings' ][ 'showReview' ]) && $social_array[ 'settings' ][ 'showReview' ] == 'Checked'? 'reviews':'';
						$render = "['" . $rating . "']";
						$this->extendScript($social_array['icons'][$icon],$render);
						add_action( 'wp_footer', array($this, 'generateGoogleReviewScript' )) ;
						$preview_area_content .= '<div class="tl-sb-google-review"></div>';
					}
					if($icon=='facebook'){
						$page_id=isset($social_array['icons'][$icon]['page_id'])?$social_array['icons'][$icon]['page_id']:'';
						$page_access_token=isset($social_array['icons'][$icon]['access_token'])?$social_array['icons'][$icon]['access_token']:'';
						$response = tlsb_rev_api_rating($page_id, $page_access_token);
						$response_data = !empty($response['data'])?$response['data']:'';
						$response_json = isset($response_data)?json_decode($response_data):'';
						if (isset($response_json->overall_star_rating)) {
							$reviews = $response_json->overall_star_rating;
							$preview_area_content .= '<div class="tl-sb-average-rating"><h4><div class="tl-sb-facebook-review">'.$reviews.'</div></h4></div>';
						}
					}
					
					$preview_area_content .= '</div>';
				}
			}
			$preview_area_content.= '</div>';			
			echo $preview_area_content ;
			endif ;
		}else{
			if( isset( $instance[ 'tl_sb_follow_id' ]) && !empty( $social_array ) ):
			$preview_area_content = '';
			$sticky_status = isset( $social_array[ 'settings' ][ 'isSticky' ]) && ( $social_array[ 'settings' ][ 'isSticky' ] ) == 'Checked' ? 'Checked' : '';
			$icon_link = isset( $social_array[ 'settings' ][ 'link_open_opt' ] ) ? $social_array[ 'settings' ][ 'link_open_opt' ] : '_self';
			$padding = isset( $social_array[ 'settings' ][ 'padding' ] ) ? 'style = "padding:'.$social_array[ 'settings' ][ 'padding' ].'px"' : '';
			$preview_area_content.= '<div class = "tl-sb-preview-area tl-sb-stickypos-inline" '. $align .'>';
			if(isset($social_array['settings']['ordering'])){
				foreach($social_array['settings']['ordering'] as $icon){
					$arr['iconDefault']=isset($social_array['icons'][$icon]['color']['bydefault'])?$social_array['icons'][$icon]['color']['bydefault']:'#fff';
					$arr['iconHover']=isset($social_array['icons'][$icon]['color']['hover'])?$social_array['icons'][$icon]['color']['hover']:'#fff';
					$arr['bgDefault']=isset($social_array['icons'][$icon]['bgcolor']['bydefault'])?$social_array['icons'][$icon]['bgcolor']['bydefault']:tlsb_generateColor($icon, 'default');
					$arr['bgHover']=isset($social_array['icons'][$icon]['bgcolor']['hover'])?$social_array['icons'][$icon]['bgcolor']['hover']:tlsb_generateColor($icon, 'hover');
					$url=isset($social_array['icons'][$icon]['url'])?stripslashes_deep($social_array['icons'][$icon]['url']):'#';
					$preview_area_content .= '<div class="tl-sb-icon-head" data-name="'. $icon .'"><div class="tl-sb-icon-wrapper"><a href="'.$url.'" target="'.$icon_link.'" '.$padding.'>'.stripslashes_deep($social_array['icons'][$icon]['content']).'</a><div class="tlsb-tooltip">'. $icon .'</div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div></div>';
				}
			}
			$preview_area_content.= '</div>';			
			echo $preview_area_content ;
			endif ;
		}
	?>
</div>
	
	<?php echo $args[ 'after_widget' ];
	}
			 
	// Widget Backend 
	public function form( $instance ) {
		
		$title				 	=  isset($instance[  'title'  ])?$instance[  'title'  ]:'New Title';
		$tlsb_widget_align		=  isset($instance[  'align'  ])?$instance[  'align'  ]:'left';
		// Widget admin form
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Alignment:' ); ?></label> 
			<select id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
				<option value='left'<?php echo ( $tlsb_widget_align == 'left' ) ? esc_attr( ' selected' ):'' ; ?>>left</option>
				<option value='center'<?php echo ( $tlsb_widget_align == 'center' ) ? esc_attr( ' selected' ):'' ; ?>>Center</option>
				<option value='right'<?php echo ( $tlsb_widget_align == 'right' ) ? esc_attr( ' selected' ):'' ; ?>>Right</option>
			</select>
		</p>
		<p>
			<select id="<?php echo $this->get_field_id( 'tl_sb_follow_id' ); ?>" name="<?php echo $this->get_field_name( 'tl_sb_follow_id' ); ?>">
				<?php
				foreach($this->tl_sb_array as $type =>$typeData){
					?>
					<optgroup label="<?php echo $type ; ?>">
					<?php
					foreach($typeData as $id =>$innerdata){
						if($id > 0){
							$follow_id=isset($instance[ 'tl_sb_follow_id' ])?$instance[ 'tl_sb_follow_id' ]:'';
					?>
						<option value="<?php echo esc_attr( $type.'.'.$id ); ?>"<?php echo ($follow_id == $type.'.'.$id)?esc_attr( ' selected' ):'' ; ?>>tl-sb-<?php echo esc_attr( $type.'-'.$id ); ?></option>
					<?php
						}
					}
					?>
					</optgroup>
					<?php
				}
				?>
			</select>
		</p>

	<?php 
	}
		 
		 
	public function update( $new_instance, $old_instance ) {
		$instance  =  array() ;
		$instance[ 'title' ]  =  ( ! empty( $new_instance[ 'title' ] ) ) ? sanitize_text_field( $new_instance[ 'title' ] ) : '' ;
		$instance[ 'tl_sb_follow_id' ]  =  ( ! empty( $new_instance[ 'tl_sb_follow_id' ] ) ) ? sanitize_text_field( $new_instance[ 'tl_sb_follow_id' ] ) : '' ;
		$instance[ 'align' ]  =  ( ! empty( $new_instance[ 'align' ] ) ) ? sanitize_text_field( $new_instance[ 'align' ] ) : '' ;

		return $new_instance ;
	}


	function generateMeta(){
		$post_id 	=  get_queried_object_id();
        $post_obj 	=  get_post( $post_id );
        $content 	=  wp_strip_all_tags(do_shortcode( $post_obj->post_content ) );
		$getTitle	=  get_the_title() ;
		$image = has_post_thumbnail() ? get_the_post_thumbnail_url() : '';
		$html = '' ;
		$html.= '<meta property = "og:locale" content = "en_US" />';
		$html.= '<meta property = "og:title" content = "'.$getTitle.'" />';
		$html.= '<meta property = "og:type" content = "website" />' ;
		$html.= '<meta property = "og:url" content = "'.urlencode(get_permalink()).'" />';
		$html.= '<meta property = "og:image" content = "'.$image.'" />';
		$html.= '<meta property = "og:image:width" content = "400" />';
		$html.= '<meta property = "og:image:height" content = "300" />';
		$html.= '<meta property = "og:site-name" content = "'.get_bloginfo('name').'" />';
		$html.= '<meta property = "og:description" content = "'.wp_strip_all_tags($content).'" />';
		
		echo $html ;
	}
	private function extendScript($icon_property=[], $render){
		$app_id=isset($icon_property['api_key'])?$icon_property['api_key']:'';
		$place_id=isset($icon_property['place_id'])?$icon_property['place_id']:'';
		$minimum_rating=isset($icon_property['min_rating'])?$icon_property['min_rating']:'';
		$this->tlsb_review_script .="<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=".$app_id."&signed_in=true&libraries=places'></script>
			  <script>
				jQuery(document).ready(function( $ ) {
					$('#".$this->id."').find('.tl-sb-google-review').googlePlaces({
						placeId: '".$place_id."' //Find placeID @: https://developers.google.com/places/place-id
						, render: " . $render . "
						, min_rating:".$minimum_rating."
						, max_rows:5
						,rotateTime: false     
					});
				});
			</script>";
	}
	public function generateGoogleReviewScript() {
		echo $this->tlsb_review_script;
			
	}
}

function Zumper_widget_enqueue_script() {   
	wp_add_inline_script( 'my_custom_script', '!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[ 0 ];
				s.parentNode.insertBefore(t,s)}(window,document,"script",
				"https://connect.facebook.net/en_US/fbevents.js");
				fbq("track", "PageView");' );	
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . '/assets/js/review/google_review.js' );
}
add_action('wp_enqueue_scripts', 'Zumper_widget_enqueue_script');

function tlsb_rev_api_rating($page_id, $page_access_token) {
	$api_url = 'https://graph.facebook.com/v4.0/' . $page_id . "?fields=overall_star_rating,name,id,ratings{recommendation_type,rating,review_text,reviewer{id,name}}&access_token=" . $page_access_token;
	
	$api_response = tlsb_urlopen($api_url);

    return $api_response;
}
function tlsb_urlopen($url, $postdata=false, $headers=array()) {
        $response = array(
            'data' => '',
            'code' => 0
        );

        $url = preg_replace('/\s+/', '+', $url);

        if(function_exists('curl_init')) {
            if (!function_exists('curl_setopt_array')) {
                function curl_setopt_array(&$ch, $curl_options) {
                    foreach ($curl_options as $option => $value) {
                        if (!curl_setopt($ch, $option, $value)) {
                            return false;
                        }
                    }
                    return true;
                }
            }
            _tlsb_curl_urlopen($url, $postdata, $headers, $response);
        } 
        return $response;
    }

    /*-------------------------------- curl --------------------------------*/
    function _tlsb_curl_urlopen($url, $postdata, $headers, &$response) {
        $c = curl_init($url);

        $data = wp_remote_get($url);
        
        if (stripos($data, "HTTP/1.0 200 Connection established\r\n\r\n") !== false) {
            $data = str_ireplace("HTTP/1.0 200 Connection established\r\n\r\n", '', $data);
        }

        list($resp_headers, $response['data']) = explode("\r\n\r\n", $data, 2);

        $response['code'] = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
    }

