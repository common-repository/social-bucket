<?php
if(!defined('ABSPATH')){
    exit;
   }
class TLSB_Follow extends Controller implements ServiceType{
	public $tlsb_unique_id;
	public $active_list_icons;
	private $tl_sb_array=[];
	private $dynamic_array=[];
	private $follow_icon_size, $follow_wrapper_class, $follow_icon_padding, $tl_social_bucket_array, $settings_area=false;	
	public function __construct($tl_sb_array=[], $tl_sb_query_var=''){
		$this->dynamic_array=$tl_sb_array;
		$this->tl_sb_array[ "tl_sb_follow_block" ] = sanitize_text_field( 'style = display:block ;' );
		$this->tl_sb_array[ "tl_sb_follow_form" ] = sanitize_text_field( 'active' );
		$this->icons=[ 'youtube', 'facebook', 'twitter', 'pinterest', 'instagram', 'linkedin', 'tumblr', 'buffer', 'reddit', 'yelp', 'houzz' ];
		
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
				$this->tl_sb_array[ "tl_sb_socialName" ] = '';
				$this->tl_sb_array[ "tl_sb_iconSize" ] = '40';
				$this->tl_sb_array[ "tl_sb_iconShape" ] = 'Circle';
				$this->tl_sb_array[ "tl_sb_iconIsSticky" ] = '';
				$this->tl_sb_array[ "tl_sb_iconStickyPos" ] = 'style = "display:none ;"';
				$this->tl_sb_array[ "tl_sb_iconStickyPosDisplay" ] = 'Left';
				$this->tl_sb_array[ "tl_sb_iconLinkOpen" ] = '_self';
				$this->tl_sb_array[ "tl_sb_previewAreaContent" ] = '';
				$this->tl_sb_array[ "tl_sb_icon_padding" ] = 2;
				$this->tl_sb_array[ "tl_sb_iconStar" ] = '';
			}
			if( $tl_sb_query_var['action'] == 'edit' ){
				$this->settings_area=true;
				$this->tl_sb_array[ "tl_SB_theId" ] =  sanitize_text_field( $tl_sb_query_var['id'] );
				$tl_sb_idArray = $tl_sb_array[ $this->tl_sb_array[ "tl_SB_theId" ] ];
				$this->tl_sb_array[ "tl_sb_socialName" ] = isset($tl_sb_idArray[ 'settings' ][ 'name' ])?$tl_sb_idArray[ 'settings' ][ 'name' ]:'';
				$this->tl_sb_array[ "tl_sb_iconSize" ] = isset($tl_sb_idArray[ 'settings' ][ 'size' ])?$tl_sb_idArray[ 'settings' ][ 'size' ]:'40';
				$this->tl_sb_array[ "tl_sb_iconShape" ] = isset($tl_sb_idArray[ 'settings' ][ 'bgShape' ])?$tl_sb_idArray[ 'settings' ][ 'bgShape' ]:'Circle';
				$this->tl_sb_array[ "tl_sb_iconIsSticky" ] = isset($tl_sb_idArray[ 'settings' ][ 'isSticky' ]) && ($tl_sb_idArray[ 'settings' ][ 'isSticky' ]) == 'Checked'?'Checked':'';
				$this->tl_sb_array[ "tl_sb_iconStickyPos" ] = ($this->tl_sb_array[ "tl_sb_iconIsSticky" ] == 'Checked')?'':'style = "display:none ;"' ;
				$this->tl_sb_array[ "tl_sb_iconStickyPosDisplay" ] = isset($tl_sb_idArray[ 'settings' ][ 'stickyPos' ])?$tl_sb_idArray[ 'settings' ][ 'stickyPos' ]:'Left' ;
				$this->tl_sb_array[ "tl_sb_iconStar" ] = isset($tl_sb_idArray[ 'settings' ][ 'addStar' ])?'checked':'' ;
				$this->tl_sb_array[ "tl_sb_icon_padding" ] = isset($tl_sb_idArray[ 'settings' ][ 'padding' ])?$tl_sb_idArray[ 'settings' ][ 'padding' ]:2;
				$this->tl_sb_array[ "tl_sb_iconLinkOpen" ] = isset($tl_sb_idArray[ 'settings' ][ 'link_open_opt' ])?$tl_sb_idArray[ 'settings' ][ 'link_open_opt' ]:'_self';
				$this->tl_sb_array[ "tl_sb_previewAreaContent" ] = '';		
				if( isset( $tl_sb_idArray[ 'icons' ] ) ){
					$this->activeIcons($tl_sb_idArray[ 'icons' ]);
					foreach( $tl_sb_idArray[ 'icons' ] as $iconkey =>$icon ){
						$this->tl_sb_array[ "tl_sb_previewAreaContent" ] .=  '<div class="tl-sb-icon-head" id="tl_sb_fe_'.$iconkey.'" data-name="'.$iconkey.'" data-save="follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>icons>'.$iconkey.'>content"><div class="tl-sb-icon-wrapper">'.stripslashes_deep( $icon[ 'content' ] ).'</div></div>';
						$tl_sb_pAIcon[ $iconkey ] = 'active';
					}
				
				}									
			}
			$this->tlsb_unique_id=$this->tl_sb_array[ "tl_SB_theId" ];
		}
		$this->follow_wrapper_class = isset($this->tl_sb_array["tl_sb_follow_form"])?' '.$this->tl_sb_array["tl_sb_follow_form"]:'';

		$this->follow_icon_size = isset($this->tl_sb_array["tl_sb_iconSize"])?$this->tl_sb_array["tl_sb_iconSize"]:40;

		$this->follow_icon_padding = isset($this->tl_sb_array["tl_sb_icon_padding"])?$this->tl_sb_array["tl_sb_icon_padding"]:2;
		
	}
	private function activeIcons($activeIcons=[]){
		$this->active_icons=array_keys($activeIcons);
		$this->active_list_icons=$this->active_icons;
	}
	public function tabList(){
		ob_start();
		?>
		<span class = "tl-sb-social-text" data-name = "follow" data-type = "tl-sb-follow" data-id = "<?php echo isset($this->tl_sb_array[ "tl_SB_theId" ])?esc_attr( $this->tl_sb_array[ "tl_SB_theId" ] ):'';?>"><i class="fas fa-eye"></i> Follow</span>
		<?php
		return ob_get_clean();
	}
	public function tabContent(){
		ob_start();
		?>		
		<div class="tl-sb-socialtype tl-sb-follow active <?php echo $this->follow_wrapper_class; ?>" data-title="Follow" data-name="tl-sb-follow">
			<div class="tl-sb-follow-form" <?php echo $this->tl_sb_array["tl_sb_follow_block"]; ?>>
				<div class="tl-sb-form-general">
					<div class="tl-sb-button-div">
						<p class="tl-sb-button-title">Click on button to add or remove icon</p>
						<?php
							echo $this->iconButton($this->icons);
						?>
					</div>
					<div class="tl-sb-preview-area atl-sb-preview-area" data-save="follow><?php echo esc_attr( $this->tl_sb_array["tl_SB_theId"] );?>>icons">
						<h3><i class="far fa-eye"></i> Preview Area</h3>
						<?php echo isset( $this->tl_sb_array["tl_sb_previewAreaContent"] )? $this->tl_sb_array["tl_sb_previewAreaContent"]:''; ?>
					</div>			
				</div>
				<?php echo $this->settingsArea($this->settings_area); ?>
				<div class = "tl-sb-form-settings-general">
					<?php
					$this->addField([
						'title'=>'Title',
						'type'=>'text',
						'class'=>'tl-sb-follow-group-name',
						'id'=>'',
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>name',
						'value'=>$this->tl_sb_array[ "tl_sb_socialName" ]
						
					]);
					$this->addField([
						'title'=>'Icon Size',
						'type'=>'comboslider',
						'class'=>'tl-sb-follow-icon-size-default',
						'value'=>$this->follow_icon_size,
						'name'=>'tl-sb-follow-icon-size-default',
						'min'=>20,
						'max'=>120,
						'step'=>1,
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>size',
						
					]);
					$this->addField([
						'title'=>'Icon shape',
						'type'=>'option',
						'class'=>'tl-sb-follow-shape',
						'value'=>$this->tl_sb_array[ "tl_sb_iconShape" ],
						'name'=>'tl-sb-follow-shape',
						'options'=>[
							'Circle'=>'Circle',
							'Square'=>'Square',
							'Rounded Square'=>'Rounded Square',
							'Hexagon'=>'Hexagon',
							'Transparent'=>'Transparent'
						],			
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>bgShape',
						
					]);
					$this->addField([
						'type'=>'number',
						'class'=>'tl-sb-icon-padding',
						'value'=>$this->follow_icon_padding,
						'name'=>'tl-sb-icon-padding',
						'step'=>'.5',
						'noticetxt'=>'<b>px</b><span class = "tl-sb-message"><i>Notice: This change will appear only on the front-end</i></span>',
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>padding',
						
					]);
					$this->addField([
						'title'=>'Link Open',
						'type'=>'option',
						'class'=>'tl-sb-icon-link-open-tab',
						'value'=>$this->tl_sb_array[ "tl_sb_iconLinkOpen" ],
						'name'=>'tl-sb-icon-link-open-tab',
						'options'=>[
							'New tab'=>'New tab',
							'Same tab'=>'Same tab'
						],
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>link_open_opt',
						
					]);
					$this->addField([
						'type'=>'conditaionaloption',
						'class'=>'tl-sb-follow-isSticky',
						'id'=>'tl-sb-follow-isSticky',
						'value'=>$this->tl_sb_array[ "tl_sb_iconIsSticky" ],
						'name'=>'tl-sb-icon-link-open-tab',
						'options'=>['New tab', 'Same tab'],
						'path'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>isSticky',
						'conditionpath'=>'follow>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>stickyPos',
						'conditionoption'=>['Left', 'Right'],
						'conditionclass'=>'tl-sb-follow-icon-stickyPos',
						'conditionname'=>'tl-sb-follow-icon-stickyPos',
						'conditionwrapclass'=>'tl-sb-stickyenable',
						'conditionvalue'=>$this->tl_sb_array[ "tl_sb_iconStickyPosDisplay" ],					
					]);
					?>					
				<div class="tl-sb-savearea">
					<div class="submit tl-sb-anchor-btn">
						<span id="tl-sb-submit-button" class="tl-sb-submit-button">Save Changes</span>
						<span class="tl-sb-submit-alert-message">Please choose at least one social icon</span>
						<span class="tl-sb-submit-share-alert">Please select at least one share network</span>
					</div>
				</div>
			</div>
		</div>		
		<?php
		return ob_get_clean();
	}
	public function script(){}
	public function tableView(){
		ob_start();
		?>
		<div class = "tl-sb-data-row tl-sb-preview-follow">
			
			<?php echo $this->viewContent(); ?>
				
		</div>
		<?php
		return ob_get_clean();
	}
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
												
												echo '</div>';
											}
										}?>
									</div>
									<div class="tl-sb-shortcode">
										<input type="text" class="tl-sb-the-shortcode" value="<?php echo esc_attr( tl_sb_shortCode( $idKey, 'follow' ) ); ?>" readonly = "readonly">
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
												<a id="tl_sb_sgroup_edit" href="admin.php?page=tl-social-bucket&action=edit&id=<?php echo esc_attr( $idKey ); ?>&type=follow" data-id="<?php echo esc_attr( $idKey ); ?>">
													<span class="dashicons dashicons-edit"></span>
												</a>
											</div>
											<div id="tl_sb_sgroup_delete" class="tl-sb-action-inner action-delete" data-id ="<?php echo esc_attr( $idKey ); ?>">
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
	}
}
