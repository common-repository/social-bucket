<?php
if(!defined('ABSPATH')){
    exit;
   }

class TLSB_Review extends Controller implements ServiceType{
	public $tlsb_unique_id;
	public $active_list_icons;
	private $tl_sb_array=[];
	private $dynamic_array=[];
	private $follow_icon_size, $follow_wrapper_class, $follow_icon_padding, $tl_social_bucket_array, $settings_area=false;
	public function __construct($tl_sb_array=[], $tl_sb_query_var=''){
		$this->dynamic_array=$tl_sb_array;
		$this->tl_sb_array[ "tl_sb_review_block" ] = sanitize_text_field( 'style = display:block ;' );
		$this->tl_sb_array[ "tl_sb_review_block" ] = sanitize_text_field( 'active' );
		$this->icons=[ 'facebook', 'yelp', 'googlemybusiness', 'yellowpages', 'healthgrades', 'fouresquare', 'angielist', 'houzz' ];
		if( isset( $tl_sb_query_var['action'] ) ){
			if( $tl_sb_query_var['action'] == 'addNew' ){
				$this->settings_area=false;
				$idKey = count( $tl_sb_array ) && !empty($tl_sb_array)>0?array_keys( $tl_sb_array ):'';
				if( is_array( $idKey ) ){
					$idKe = $idKey[ count( $idKey ) -1 ];
				}else{
					$idKe = '0' ;
				}
				$this->tl_sb_array[ "tl_SB_theId" ] = ( ( int )$idKe) + 1;
				$this->tl_sb_array[ "tl_sb_review_socialName" ] = '';
				$this->tl_sb_array[ "tl_sb_review_iconSize" ] = 40;
				$this->tl_sb_array[ "tl_sb_review_iconShape" ] = 'Circle';
				$this->tl_sb_array[ "tl_sb_review_showReview" ] = '' ;
				$this->tl_sb_array[ "tl_sb_review_iconLinkOpen" ] = '_self';
				$this->tl_sb_array[ "tl_sb_review_previewAreaContent" ] = '';
				$this->tl_sb_array[ "tl_sb_review_icon_padding" ] = 2;
				$this->tl_sb_array[ "tl_sb_review_iconStar" ] = '';
			}
			if( $tl_sb_query_var['action'] == 'edit' ){
				$this->settings_area=true;
				$this->tl_sb_array[ "tl_SB_theId" ] =  sanitize_text_field($tl_sb_query_var[ 'id' ] );
				$tl_sb_idArray = $tl_sb_array[ $this->tl_sb_array[ "tl_SB_theId" ] ];
				$this->tl_sb_array[ "tl_sb_review_socialName" ] = isset( $tl_sb_idArray[ 'settings' ][ 'name' ] ) ? $tl_sb_idArray[ 'settings' ][ 'name' ] : '';
				$this->tl_sb_array[ "tl_sb_review_iconSize" ] = isset( $tl_sb_idArray[ 'settings' ][ 'size' ] ) ? $tl_sb_idArray[ 'settings' ][ 'size' ] : 40 ;
				$this->tl_sb_array[ "tl_sb_review_iconShape" ] = isset( $tl_sb_idArray[ 'settings' ][ 'bgShape' ] ) ? $tl_sb_idArray[ 'settings' ][ 'bgShape' ] : 'Circle';
				$this->tl_sb_array[ "tl_sb_review_showReview" ] = isset( $tl_sb_idArray[ 'settings' ][ 'showReview' ] ) && ( $tl_sb_idArray[ 'settings' ][ 'showReview' ] ) == 'Checked' ? 'Checked' : '';			
				$this->tl_sb_array[ "tl_sb_review_iconStar" ] = isset( $tl_sb_idArray[ 'settings' ][ 'addStar' ] ) && $tl_sb_idArray[ 'settings' ][ 'addStar' ] == 'Checked' ? ' checked' : '' ;
				$this->tl_sb_array[ "tl_sb_review_icon_padding" ] = isset( $tl_sb_idArray[ 'settings' ][ 'padding' ] ) ? $tl_sb_idArray[ 'settings' ][ 'padding' ] : 2 ;
				$this->tl_sb_array[ "tl_sb_review_iconLinkOpen" ] = isset( $tl_sb_idArray[ 'settings' ][ 'link_open_opt' ] ) ? $tl_sb_idArray[ 'settings' ][ 'link_open_opt' ] : '_self';
				$this->tl_sb_array[ "tl_sb_review_previewAreaContent" ] = '';	


				
				$star = '';
				if(isset($tl_sb_idArray[ 'settings' ][ 'addStar' ]) && $tl_sb_idArray[ 'settings' ][ 'addStar' ] == 'Checked') {
					$icon_size = $this->tl_sb_array[ "tl_sb_review_iconSize" ]/6;
					$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
					$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
					$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
					$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
					$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
				}
					
				if( isset( $tl_sb_idArray[ 'icons' ] ) ){
					$this->activeIcons($tl_sb_idArray[ 'icons' ]);
					foreach( $tl_sb_idArray[ 'icons' ] as $iconkey =>$icon ){
						$this->tl_sb_array[ "tl_sb_review_previewAreaContent" ] .=  '<div class="tl-sb-icon-head" id="tl_sb_fe_'.$iconkey.'" data-name="'.$iconkey.'" data-save="review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>icons>'.$iconkey.'>content"><div class="tl-sb-icon-wrapper">'.stripslashes_deep( $icon[ 'content' ] ).'</div>';
						
						$this->tl_sb_array[ "tl_sb_review_previewAreaContent" ] .=  '<div class="starwrap">' . $star . '</div></div>';
						$tl_sb_pAIcon[ $iconkey ] = 'active';
					}
					//$this->loadIconSettings( $this->active_icons, $this->tl_sb_array[ "tl_SB_theId" ], 'review' );				
				}
			}
			$this->tlsb_unique_id=$this->tl_sb_array[ "tl_SB_theId" ];
		}
	}
	public function tabList(){
		ob_start();
		?>
		<span class = "tl-sb-social-text active" data-name = "review" data-type = "tl-sb-review"  data-id = "<?php echo (!empty($this->tl_sb_array[ "tl_SB_theId" ])?esc_attr( $this->tl_sb_array[ "tl_SB_theId" ] ):'');?>"><i class="fab fa-rev"></i> Review</span>
		<?php
		return ob_get_clean();
	}
	private function activeIcons($activeIcons=[]){
		$this->active_icons=array_keys($activeIcons);
		$this->active_list_icons=$this->active_icons;
	}
	public function tabContent(){
		ob_start();
		?>
		<div class = "tl-sb-socialtype tl-sb-review active" data-title = "review" data-name = "tl-sb-review">
			<div class = "tl-sb-review-form <?php echo $this->tl_sb_array[ "tl_sb_review_block" ]; ?>">
				<div class = "tl-sb-form-general">
					<div class = "tl-sb-button-div">
						<p class="tl-sb-button-title">Click on button to add or remove icon</p>
						<?php
							echo $this->iconButton($this->icons);
						?>
					</div>
					<div class="tl-sb-preview-area atl-sb-preview-area" data-save="review><?php echo esc_attr( $this->tl_sb_array["tl_SB_theId"] );?>>icons">
						<h3><i class="far fa-eye"></i> Preview Area</h3>
						<?php echo isset( $this->tl_sb_array["tl_sb_review_previewAreaContent"] )? $this->tl_sb_array["tl_sb_review_previewAreaContent"]:''; ?>
					</div>			
				</div>
				<?php echo $this->settingsArea($this->settings_area); ?>
				<div class = "tl-sb-form-settings-general">
					<?php
					$this->addField([
						'title'=>'Title',
						'type'=>'text',
						'name'=>'tl-sb-follow-group-name',
						'class'=>'tl-sb-follow-group-name',
						'id'=>'',
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>name',
						'value'=>$this->tl_sb_array[ "tl_sb_review_socialName" ],
					]);
					$this->addField([
						'title'=>'Display Rating below icon :',
						'type'=>'conditaionaloption',
						'class'=>'tl-sb-follow-addStar',
						'id'=>'tl-sb-review-addStar',
						'value'=>$this->tl_sb_array[ "tl_sb_review_iconStar" ],
						'name'=>'tl-sb-follow-addStar',
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>isSticky',									
					]);
					$this->addField([
						'title'=>'Display Review:',
						'type'=>'conditaionaloption',
						'class'=>'tl-sb-follow-isSticky',
						'id'=>'tl-sb-review-showReview',
						'value'=>$this->tl_sb_array[ "tl_sb_review_iconStar" ],
						'name'=>'tl-sb-follow-addStar',
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>isSticky',									
					]);
					$this->addField([
						'title'=>'Icon Size',
						'type'=>'comboslider',
						'class'=>'tl-sb-follow-icon-size-default',
						'value'=>$this->tl_sb_array[ "tl_sb_review_iconSize" ],
						'name'=>'tl-sb-follow-icon-size-default',
						'min'=>20,
						'max'=>120,
						'step'=>1,
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>size',					
					]);
					$this->addField([
						'type'=>'option',
						'class'=>'tl-sb-follow-shape',
						'value'=>$this->tl_sb_array[ "tl_sb_review_iconShape" ],
						'name'=>'tl-sb-follow-shape',
						'options'=>[
							'Circle'=>'Circle',
							'Square'=>'Square',
							'Rounded Square'=>'Rounded Square',
							'Hexagon'=>'Hexagon',
							'Transparent'=>'Transparent'
						],				
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>bgShape',
					]);
					
					$this->addField([
						'title'=>'Padding',
						'type'=>'number',
						'class'=>'tl-sb-icon-padding',
						'value'=>$this->tl_sb_array[ "tl_sb_review_icon_padding" ],
						'name'=>'tl-sb-icon-padding',
						'step'=>'.5',
						'noticetxt'=>'<b>px</b><span class = "tl-sb-message"><i>Notice: This change will appear only on the front-end</i></span>',
						'path'=>'review>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>padding',
					]);
					?>					
				<div class="tl-sb-savearea">
					<div class="submit tl-sb-anchor-btn">
						<span id="tl-sb-submit-button" class="tl-sb-submit-button">Save Changes</span>
						<span class="tl-sb-submit-alert-message">Please select at least one social network</span>
					</div>
				</div>
			</div>
		</div>
		</div>
		<?php
		return ob_get_clean();
	}// end tabContent
	public function tableView(){
		ob_start();
		?>
		<div class = "tl-sb-data-row tl-sb-preview-review">
			
			<?php echo $this->viewContent(); ?>
				
		</div>
		<?php
		return ob_get_clean();
	}
	public function script(){}
	public function viewContent(){
		ob_start();
		?>
		<div class = "tl-sb-innertab">
		<?php
			if( is_array( $this->dynamic_array ) ){
				foreach( $this->dynamic_array as $idKey =>$id ){					
					if( is_array( $id ) ){
						?>
						<div class="tl-sb-data-inner-row">
							<div class="tl-sb-dt-property">
								<div class="tl-sb-dt-property-inner">
									<div class="tl-sb-preview-area">
										<?php
										if( is_array( $id[ 'settings' ][ 'ordering' ] ) ){
											foreach( $id[ 'settings' ][ 'ordering' ] as $icon ){
												echo '<div class="tl-sb-icon-head"><div class="tl-sb-icon-wrapper">'. stripslashes_deep( $id['icons'][$icon][ 'content' ] ) .'</div>';
												if(isset($id[ 'settings' ][ 'addStar' ]) && $id[ 'settings' ][ 'addStar' ] == 'Checked') {
													$icon_size = isset($id[ 'settings' ][ 'size' ]) ? $id[ 'settings' ][ 'size' ]/6 : 6;
													$star ='';
													$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
													$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
													$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
													$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
													$star .='<span class="tl-star"><img src="' . TLSB_PLUGIN_URL . 'assets/images/star.png" style="height:' . $icon_size . 'px;"></span>';
													echo '<div class="starwrap">' . $star . '</div>';
												}
												echo '</div>';
											}
										}?>
									</div>
									<div class="tl-sb-shortcode">
										<input type="text" class="tl-sb-the-shortcode" value="<?php echo esc_attr( tl_sb_shortCode( $idKey, 'review' ) ); ?>" readonly = "readonly">
										<div class="tl-sb-anchor-btn">
											<span class="tl-sb-copy-button copy-this">
												<i class="fas fa-copy" title="Copy Shortcode"></i>
											</span>
											<span class="copied-to-clipboard code-msg" style="display: none ;">Copied</span>
										</div>										
									</div>
									<div class="tl-sb-data-edit">
										<div class="tl-sb-action">
											<div class="tl-sb-action-inner action-edit">
												<a id="tl_sb_sgroup_edit" href="admin.php?page=tl-social-bucket&action=edit&id=<?php echo esc_attr( $idKey ); ?>&type=review" data-id="<?php echo esc_attr( $idKey ); ?>">
													<span class="dashicons dashicons-edit"></span>
												</a>
											</div>
											<div id="tl_sb_sgroup_delete" class="tl-sb-action-inner action-delete" data-id =<?php echo esc_attr( $idKey ); ?>">
												<span class="dashicons dashicons-trash"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
			}
			?>
			<div class="tl-sb-data-inner-row add-new">
					<div class="add-new-btn-block">
						<div class="add-new-btn-inner">
							<a class = "tl-sb-anchor-btn tl-sb-add-new-button" href="admin.php?page=tl-social-bucket&amp;action=addNew">
								<span class = "dashicons dashicons-plus"></span>
								<span class = "tl-sb-btn-text">Add New</span>
							</a>
						</div>
					</div>
				</div>
		</div>
			<?php
		return ob_get_clean();
	}// end viewContent
}
