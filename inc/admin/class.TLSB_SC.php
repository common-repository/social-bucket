<?php
class TLSB_SC{
	public static $tlsb_Array;
	public function generateShortcode($id, $key){
		$tl_sb_content		=	sanitize_text_field('[tl-sb id='.$id.' key='.$key.']');
		return $tl_sb_content;
	}
	public static function generateContext($atts){		
		$atts = shortcode_atts(
			array(
				'id' => '',
				'key'=>'',
			), $atts, 'tl-sb' );	
			
		$returnableValue=self::expandCode($atts['id'], $atts['key']);
		return $returnableValue;
	}
	public static function expandCode($codeId, $key){
		$tl_social_bucket_array=get_option('tl_sb_settings',[]);
		self::$tlsb_Array=$tl_social_bucket_array;
		if( empty($tl_social_bucket_array[$key]) ) return ;
		
		if( !isset($tl_social_bucket_array[$key][$codeId]) ) return ;

		$tl_sb_idArray=$tl_social_bucket_array[$key][$codeId];
		if( $key == "share" ){
			if($tl_sb_idArray!='' && count($tl_sb_idArray)>0){			
			$tl_sb_isStickyPos			=	isset($tl_sb_idArray['settings']['isSticky']) && ($tl_sb_idArray['settings']['isSticky'])=='Checked'?'Checked':'';
			$tl_sb_iconStickyPosDisplay	=	isset($tl_sb_idArray['settings']['stickyPos'])?$tl_sb_idArray['settings']['stickyPos']:'left';
			$tl_sb_iconLinkOpen			=	isset($tl_sb_idArray['settings']['link_open_opt'])?$tl_sb_idArray['settings']['link_open_opt']:'_self';
			$tl_sb_iconPadding			=	isset($tl_sb_idArray['settings']['padding'])?'style="padding:'.$tl_sb_idArray['settings']['padding'].'px"':'';
			$tl_sb_iconName				=	isset( $tl_sb_idArray['settings']['name'] ) ? $tl_sb_idArray['settings']['name'] : '';
			$tl_sb_previewAreaTotalContent='';				
			$tl_sb_previewAreaContent='';
			
			if(isset($tl_sb_idArray['settings']['ordering'])){
				foreach($tl_sb_idArray['settings']['ordering'] as $icon){
					$arr['iconDefault']=isset($tl_sb_idArray['icons'][$icon]['color']['bydefault'])?$tl_sb_idArray['icons'][$icon]['color']['bydefault']:tlsb_generateIconColor( $icon, 'default' );
					$arr['iconHover']=isset( $tl_sb_idArray['icons'][$icon]['color']['hover'] )?$tl_sb_idArray['icons'][$icon]['color']['hover']:tlsb_generateIconColor( $icon, 'hover' );
					$arr['bgDefault']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault']:tlsb_generateColor($icon, 'default');
					$arr['bgHover']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['hover'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['hover']:tlsb_generateColor($icon, 'hover');
					$url= esc_url( self::shareUrl($icon) );
					$tl_sb_previewAreaContent .= '<div class="tl-sb-icon-head" data-name="'. $icon. '"><div class="tl-sb-icon-wrapper"><a href="'.$url.'" target="'.$tl_sb_iconLinkOpen.'" '.$tl_sb_iconPadding.'>'.stripslashes_deep($tl_sb_idArray['icons'][$icon]['content']).'</a><div class="tlsb-tooltip">'. $icon .'</div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div></div>';					
				}
			}
			$tl_sb_previewAreaTotalContent.='<div class="tl-sb-preview-area tl-sb-stickypos-inline">';
			$tl_sb_previewAreaTotalContent.='<div class="tl-sb-title"><h4>'.$tl_sb_iconName.'</h4></div><div class="tl-sb-icons">';
			$tl_sb_previewAreaTotalContent.=$tl_sb_previewAreaContent;
			$tl_sb_previewAreaTotalContent.='</div></div>';
			
			return $tl_sb_previewAreaTotalContent;
		}
		}else if($key == 'review'){			
			if($tl_sb_idArray!='' && count($tl_sb_idArray)>0){					
				$tl_sb_iconPadding			=	isset($tl_sb_idArray['settings']['padding'])?'style="padding:'.$tl_sb_idArray['settings']['padding'].'px"':'';
				$tl_sb_iconName				=	isset( $tl_sb_idArray['settings']['name'] ) ? $tl_sb_idArray['settings']['name'] : '';
				$tl_sb_iconsize				=	isset( $tl_sb_idArray['settings']['size'] ) ? $tl_sb_idArray['settings']['size'] : '';				
				$tl_sb_previewAreaTotalContent='';				
				$tl_sb_previewAreaContent='';
				if(isset($tl_sb_idArray['settings']['ordering'])){
					foreach($tl_sb_idArray['settings']['ordering'] as $icon){
						
					//	self::tlsb_getStar($icon, $tl_sb_idArray['icons'][$icon]);

						$arr['iconDefault']=isset($tl_sb_idArray['icons'][$icon]['color']['bydefault'])?$tl_sb_idArray['icons'][$icon]['color']['bydefault']:tlsb_generateIconColor( $icon, 'default' );
						$arr['iconHover']=isset($tl_sb_idArray['icons'][$icon]['color']['hover'])?$tl_sb_idArray['icons'][$icon]['color']['hover']:tlsb_generateIconColor( $icon, 'hover' );
						$arr['bgDefault']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault']:tlsb_generateColor($icon, 'default');
						$arr['bgHover']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['hover'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['hover']:tlsb_generateColor($icon, 'hover');				
						$tl_sb_previewAreaContent .= '<div class="tl-sb-icon-head" data-name="'. $icon .'"><div class="tl-sb-icon-wrapper"><a href="#" target="_self" '.$tl_sb_iconPadding.'>'.stripslashes_deep($tl_sb_idArray['icons'][$icon]['content']).'</a><div class="tlsb-tooltip">'. $icon .'</div><div class="tlsb-review-'.$icon.'"></div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div></div>';												
					}
				}
				
				$tl_sb_previewAreaTotalContent .='<div class="tl-sb-preview-area tl-sb-stickypos-inline">';	
				$tl_sb_previewAreaTotalContent.='<div class="tl-sb-title"><h4>'.$tl_sb_iconName.'</h4></div><div class="tl-sb-icons">';
				$tl_sb_previewAreaTotalContent .=$tl_sb_previewAreaContent;	
				$tl_sb_previewAreaTotalContent .='</div></div>';				
				return $tl_sb_previewAreaTotalContent;
			}
		}else{
			if($tl_sb_idArray!='' && count($tl_sb_idArray)>0){			
				$tl_sb_isStickyPos			=	isset($tl_sb_idArray['settings']['isSticky']) && ($tl_sb_idArray['settings']['isSticky'])=='Checked'?'Checked':'';
				$tl_sb_iconStickyPosDisplay	=	isset($tl_sb_idArray['settings']['stickyPos'])?$tl_sb_idArray['settings']['stickyPos']:'left';
				$tl_sb_iconLinkOpen			=	isset($tl_sb_idArray['settings']['link_open_opt'])?$tl_sb_idArray['settings']['link_open_opt']:'_self';
				$tl_sb_iconPadding			=	isset($tl_sb_idArray['settings']['padding'])?'style="padding:'.$tl_sb_idArray['settings']['padding'].'px"':'';
				$tl_sb_iconName				=	isset( $tl_sb_idArray['settings']['name'] ) ? $tl_sb_idArray['settings']['name'] : '';
				$tl_sb_iconsize				=	isset( $tl_sb_idArray['settings']['size'] ) ? $tl_sb_idArray['settings']['size'] : '';				
				$tl_sb_previewAreaTotalContent='';				
				$tl_sb_previewAreaContent='';
				if(isset($tl_sb_idArray['settings']['ordering'])){
					foreach($tl_sb_idArray['settings']['ordering'] as $icon){
						$arr['iconDefault']=isset($tl_sb_idArray['icons'][$icon]['color']['bydefault'])?$tl_sb_idArray['icons'][$icon]['color']['bydefault']:tlsb_generateIconColor( $icon, 'default' );
						$arr['iconHover']=isset($tl_sb_idArray['icons'][$icon]['color']['hover'])?$tl_sb_idArray['icons'][$icon]['color']['hover']:tlsb_generateIconColor( $icon, 'hover' );
						$arr['bgDefault']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['bydefault']:tlsb_generateColor($icon, 'default');
						$arr['bgHover']=isset($tl_sb_idArray['icons'][$icon]['bgcolor']['hover'])?$tl_sb_idArray['icons'][$icon]['bgcolor']['hover']:tlsb_generateColor($icon, 'hover');
						$url=isset($tl_sb_idArray['icons'][$icon]['url'])?stripslashes_deep($tl_sb_idArray['icons'][$icon]['url']):'#';
						$tl_sb_previewAreaContent .= '<div class="tl-sb-icon-head" data-name="'. $icon .'"><div class="tl-sb-icon-wrapper"><a href="'.$url.'" target="'.$tl_sb_iconLinkOpen.'" '.$tl_sb_iconPadding.'>'.stripslashes_deep($tl_sb_idArray['icons'][$icon]['content']).'</a><div class="tlsb-tooltip">'. $icon .'</div><div class="tl-sb-icon-individual-data" style="display:none;">'.json_encode($arr).'</div></div></div>';												
					}
				}
				
				if($tl_sb_isStickyPos=='Checked'){
					$tl_sb_previewAreaTotalContent .='<div class="tl-sb-preview-area tl-sb-stickypos-'.$tl_sb_iconStickyPosDisplay.'">';
					$tl_sb_previewAreaTotalContent .=$tl_sb_previewAreaContent;
					$tl_sb_previewAreaTotalContent .='</div>';
				}else{
					$tl_sb_previewAreaTotalContent .='<div class="tl-sb-preview-area tl-sb-stickypos-inline">';	
					$tl_sb_previewAreaTotalContent.='<div class="tl-sb-title"><h4>'.$tl_sb_iconName.'</h4></div><div class="tl-sb-icons">';
					$tl_sb_previewAreaTotalContent .=$tl_sb_previewAreaContent;	
					$tl_sb_previewAreaTotalContent .='</div></div>';
				}
				return $tl_sb_previewAreaTotalContent;
			}
		}
	}
	
	public static function shareUrl( $iconname = '' ){
		$url = urlencode(get_permalink());
		if(class_exists('TLSB_ShareView')){
			return tlsb_getUrlContent( $iconname, $url);
		}
	}
	public static function addStar($icon_size){
		$html='';
		for($i=0; $i<5; $i++){
			$html .= '<span class="tl-star"><img src="'. TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size/6 . 'px;"></span>';
		}
		return $html;
	}
	public static $iconarray;
	public static function tlsb_getStar($iconname='', $iconarray=[]){	
	if($iconname){
		switch($iconname){
			case 'googlemybusiness':
				if(!empty($iconarray) && isset($iconarray))self::$iconarray=$iconarray;
				$content_text='';
				add_action('wp_footer', array('TLSB_SC', 'tlsb_googleMybusiness'), 20, 2);			
			break;
			default:
			
			break;
		}
	}
}
	public static function tlsb_googleMybusiness(){
		//echo '<pre>';
		//print_r(self::$iconarray);die;
		$appId=self::$iconarray['api_key'];
		$placeId=self::$iconarray['place_id'];
		$minRating=self::$iconarray['min_rating'];
		/*echo '<script src="'.plugins_url( '/assets/js/review/google_review.js', dirname( dirname( __FILE__ ) ) ).'"></script><script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key='.$appId.'&signed_in=true&libraries=places"></script>
		<script>
			jQuery(document).ready(function( $ ) {
			   $(".tlsb-review-googlemybusiness").googlePlaces({
					placeId: "'.$placeId.'" 
				  , render: ["rating"]
				  , min_rating:'.$minRating.'
				  , max_rows:5
				  ,rotateTime: false								 
			   });
			});
		</script>';*/
	}
}
