<?php
if(!defined('ABSPATH')){
    exit;
   }
class TLSB_Share extends Controller implements ServiceType{
	public $tlsb_unique_id;
	public $active_list_icons;
	private $tl_sb_array=[];
	private $dynamic_array=[];
	private $follow_icon_size, $share_wrapper_class, $share_icon_padding, $tl_sb_array_array, $settings_area=false;
	public function __construct($tl_sb_array=[], $tl_sb_query_var=''){
		$this->dynamic_array=$tl_sb_array;
		$this->tl_sb_array[ "tl_sb_share_block" ] = sanitize_text_field( 'style = display:block ;' );
		$this->tl_sb_array[ "tl_sb_share_form" ] = sanitize_text_field( 'active' );
		$this->icons=[ 'facebook', 'pinterest', 'twitter', 'whatsapp', 'linkedin', 'tumblr', 'buffer', 'reddit' ];
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
				$this->tl_sb_array[ "tl_sb_share_socialName" ] = '';
				$this->tl_sb_array[ "tl_sb_share_iconSize" ] = 40;
				$this->tl_sb_array[ "tl_sb_share_iconShape" ] = 'Circle';
				$this->tl_sb_array[ "tl_sb_share_iconIsSticky" ] = '';
				$this->tl_sb_array[ "tl_sb_share_iconStickyPos" ] = 'style = "display:none ;"';
				$this->tl_sb_array[ "tl_sb_share_iconStickyPosDisplay" ] = 'Left';
				$this->tl_sb_array[ "tl_sb_share_iconLinkOpen" ] = '_self';
				$this->tl_sb_array[ "tl_sb_share_previewAreaContent" ] = '';
				$this->tl_sb_array[ "tl_sb_share_icon_padding" ] = 2;
				$this->tl_sb_array[ "tl_sb_share_iconStar" ] = '';
				$this->tl_sb_array[ "tl_sb_share_iconPosTop" ] = '';
				$this->tl_sb_array[ "tl_sb_share_iconPosBottom" ] = 'checked';
				$this->tl_sb_array[ "tl_sb_share_customLink" ] = '';			
				$this->tl_sb_array[ "tl_sb_share_link" ] = ['postslug'];
				$this->tl_sb_array[ "tl_sb_share_iconPlacement" ] = [];
				$this->tl_sb_array[ "tl_sb_share_customLink_show" ] =  'display:none ;';
			}
			if( $tl_sb_query_var['action'] == 'edit' ){
				$this->settings_area=true;
				$this->tl_sb_array[ "tl_SB_theId" ] =  sanitize_text_field($tl_sb_query_var[ 'id' ] );
				$tl_sb_idArray = $tl_sb_array[ $this->tl_sb_array[ "tl_SB_theId" ] ];
				$this->tl_sb_array[ "tl_sb_share_socialName" ] = isset( $tl_sb_idArray[ 'settings' ][ 'name' ] )? $tl_sb_idArray[ 'settings' ][ 'name' ] : '';
				$this->tl_sb_array[ "tl_sb_share_iconSize" ] = isset( $tl_sb_idArray[ 'settings' ][ 'size' ]) ? $tl_sb_idArray[ 'settings' ][ 'size' ] : 40;
				$this->tl_sb_array[ "tl_sb_share_iconShape" ] = isset( $tl_sb_idArray[ 'settings' ][ 'bgShape' ]) ? $tl_sb_idArray[ 'settings' ][ 'bgShape' ] : 'Circle';
				$this->tl_sb_array[ "tl_sb_share_link" ] = isset( $tl_sb_idArray[ 'settings' ][ 'slug' ]) ? [$tl_sb_idArray[ 'settings' ][ 'slug' ]] : ['postslug'];
				$this->tl_sb_array[ "tl_sb_share_customLink" ] = ( $this->tl_sb_array[ "tl_sb_share_link" ] == "customslug" ) && isset( $tl_sb_idArray[ 'settings' ][ 'customSlug' ]) ? $tl_sb_idArray[ 'settings' ][ 'customSlug' ] : '#';
				$this->tl_sb_array[ "tl_sb_share_customLink_show" ] = $this->tl_sb_array[ "tl_sb_share_link" ] == 'customslug' ? 'display:block ;' : 'display:none ;';
				$this->tl_sb_array[ "tl_sb_share_iconPosTop" ] = isset( $tl_sb_idArray[ 'settings' ][ 'iconPosTop' ]) && ($tl_sb_idArray[ 'settings' ][ 'iconPosTop' ]) == 'Checked' ? 'Checked' : '';
				$this->tl_sb_array[ "tl_sb_share_iconPosBottom" ] = isset( $tl_sb_idArray[ 'settings' ][ 'iconPosBottom' ]) && ($tl_sb_idArray[ 'settings' ][ 'iconPosBottom' ]) == 'Checked' ? 'Checked':'';
				$this->tl_sb_array[ "tl_sb_share_iconPlacement" ] = isset( $tl_sb_idArray[ 'settings' ][ 'placement' ] ) ? $tl_sb_idArray[ 'settings' ][ 'placement' ] : [];
				$this->tl_sb_array[ "tl_sb_share_iconIsSticky" ] = isset( $tl_sb_idArray[ 'settings' ][ 'isSticky' ] ) && ( $tl_sb_idArray[ 'settings' ][ 'isSticky' ]) == 'Checked' ? 'Checked' : '';
				$this->tl_sb_array[ "tl_sb_share_iconStickyPos" ] = ($this->tl_sb_array[ "tl_sb_share_iconIsSticky" ] == 'Checked')?'':'style = "display:none ;"';
				$this->tl_sb_array[ "tl_sb_share_iconStickyPosDisplay" ] = isset($tl_sb_idArray[ 'settings' ][ 'stickyPos' ])?$tl_sb_idArray[ 'settings' ][ 'stickyPos' ]:'Left';		
				$this->tl_sb_array[ "tl_sb_share_iconStar" ] = isset( $tl_sb_idArray[ 'settings' ][ 'addStar' ] ) ? 'checked' : '';
				$this->tl_sb_array[ "tl_sb_share_icon_padding" ] = isset( $tl_sb_idArray[ 'settings' ][ 'padding' ] ) ? $tl_sb_idArray[ 'settings' ][ 'padding' ] : 2;
				$this->tl_sb_array[ "tl_sb_share_iconLinkOpen" ] = isset( $tl_sb_idArray[ 'settings' ][ 'link_open_opt' ] ) ? $tl_sb_idArray[ 'settings' ][ 'link_open_opt' ] : '_self';
				$this->tl_sb_array[ "tl_sb_share_previewAreaContent" ] = '';		
				if( isset( $tl_sb_idArray[ 'icons' ] ) ){
					$this->activeIcons($tl_sb_idArray[ 'icons' ]);
					foreach( $tl_sb_idArray[ 'icons' ] as $iconkey => $icon ){
						$this->tl_sb_array[ "tl_sb_share_previewAreaContent" ] .=  '<div class="tl-sb-icon-head" id="tl_sb_fe_'.$iconkey.'" data-name="'.$iconkey.'" data-save="share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>icons>'.$iconkey.'>content"><div class="tl-sb-icon-wrapper">'.stripslashes_deep( $icon[ 'content' ] ).'</div></div>';
						$tl_sb_pAIcon[ $iconkey ] = 'active';
					}
				
				}				
			}
			$this->tlsb_unique_id=$this->tl_sb_array[ "tl_SB_theId" ];
		}
		
		$this->share_wrapper_class = isset($this->tl_sb_array["tl_sb_share_form"])?' '.$this->tl_sb_array["tl_sb_share_form"]:'';

		$this->follow_icon_size = isset($this->tl_sb_array["tl_sb_share_iconSize"])?$this->tl_sb_array["tl_sb_share_iconSize"]:40;

		$this->share_icon_padding = isset($this->tl_sb_array["tl_sb_share_icon_padding"])?$this->tl_sb_array["tl_sb_share_icon_padding"]:2;

		$this->tl_sb_array['share_single_posts']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['list'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['list']:[];
		$this->tl_sb_array['share_single_post_sticky_value']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['sticky'])?[$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['sticky']]:['None'];
		$this->tl_sb_array['share_single_post_standard_value']	=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['standard'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['singlePost']['standard']:[];

		$this->tl_sb_array['share_page_posts']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['list'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['list']:[];
		$this->tl_sb_array['share_page_pos_sticky_value']		=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['sticky'])?[$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['sticky']]:['None'];
		$this->tl_sb_array['share_page_pos_standard_value']		=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['standard'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['page']['standard']:[];

		$this->tl_sb_array['share_blog_posts']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['list'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['list']:[];
		$this->tl_sb_array['share_blog_pos_sticky_value']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['sticky'])?[$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['sticky']]:['None'];
		$this->tl_sb_array['share_blog_pos_standard_value']=	isset($this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['standard'])?$this->tl_sb_array[ "tl_sb_share_iconPlacement" ]['blogPage']['standard']:[];
	}
	public function tabList(){
		ob_start();
		?>
		<span class = "tl-sb-social-text" data-name = "share" data-type = "tl-sb-share"  data-id = "<?php echo isset($this->tl_sb_array[ "tl_SB_theId" ])?esc_attr( $this->tl_sb_array[ "tl_SB_theId" ] ):'';?>"><i class="fas fa-share-alt"></i>Share</span>
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
		<div class = "tl-sb-socialtype tl-sb-share active <?php echo $this->share_wrapper_class; ?>" data-title = "share" data-name = "tl-sb-share">
			<div class = "tl-sb-share-form" <?php echo $this->tl_sb_array[ "tl_sb_share_block" ]; ?>>
				<div class = "tl-sb-form-general">
					<div class = "tl-sb-button-div">
						<p class="tl-sb-button-title">Click on button to add or remove icon</p>
						<?php
							echo $this->iconButton($this->icons);
						?>
					</div>
					<div class="tl-sb-preview-area atl-sb-preview-area" data-save="share><?php echo esc_attr( $this->tl_sb_array["tl_SB_theId"] );?>>icons">
						<h3><i class="far fa-eye"></i> Preview Area</h3>
						<?php echo isset( $this->tl_sb_array["tl_sb_share_previewAreaContent"] )? $this->tl_sb_array["tl_sb_share_previewAreaContent"]:''; ?>
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
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>name',
						'value'=>$this->tl_sb_array[ "tl_sb_share_socialName" ],
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
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>size',					
					]);
					$this->addField([
						'type'=>'option',
						'class'=>'tl-sb-follow-shape',
						'value'=>$this->tl_sb_array[ "tl_sb_share_iconShape" ],
						'name'=>'tl-sb-follow-shape',
						'options'=>[
							'Circle'=>'Circle',
							'Square'=>'Square',
							'Rounded Square'=>'Rounded Square',
							'Hexagon'=>'Hexagon',
							'Transparent'=>'Transparent'
						],				
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>bgShape',
					]);
					$this->addField([
						'title'=>'Target Url',
						'type'=>'group-option',
						'class'=>'definedslug',
						'subset'=>[
							[
								'type'=>'radiobutton',							
								'wraper_class'=>'definedslug-opt',							
								'name'=>'tlsbshareslug',
								'checkvalue'=>$this->tl_sb_array[ "tl_sb_share_link" ],
								'value'=>'homepage',
								'content_text'=>'Homepage\'s url',
							],
							[
								'type'=>'radiobutton',							
								'wraper_class'=>'definedslug-opt',							
								'name'=>'tlsbshareslug',
								'checkvalue'=>$this->tl_sb_array[ "tl_sb_share_link" ],
								'value'=>'postslug',
								'content_text'=>'Individual Post/Page\'s url',
							],
							[
								'type'=>'radiobutton',							
								'wraper_class'=>'definedslug-opt',							
								'name'=>'tlsbshareslug',
								'checkvalue'=>$this->tl_sb_array[ "tl_sb_share_link" ],
								'value'=>'customslug',
								'content_text'=>'Custom url',
							],
						],
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>slug',
					]);
					/*
					*for post type selection
					*Added required fields and correspondence values
					*set function getPosts to retrive all posttypes
					*
					*/
					$this->addField([
						'title'=>'Visiblity in single post',
						'type'=>'group-option',
						'groupLabel'=>'Click each of the other option while holding down the Ctrl key to select multiple post type',
						'subset'=>[
							[
							'type'=>'right-block-option',			
							'wraper_class'=>'sb-block-value-block',
							'class'=>'tl-sb-icon-placement',
							'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>singlePost>list',
							'subset'=>[
								[
									'type'=>'multiple-select',
									'class'=>'tl-sb-single-post tlsb-page',
									'value'=>$this->tl_sb_array['share_single_posts'],
									'options'=>$this->getPosts(),
									'wraper_class'=>'',
									],
								],
							],
							[
								'type'=>'single-element',
								'wraper_class'=>'tl-sticky-option',
								'subset'=>[
									[
										'type'=>'single-element',
										'wraper_class'=>'sb-block-value-block',
										'subset'=>[
										[
											'title'=>'Placement',
											'type'=>'right-block-option',			
											'wraper_class'=>'tlsb-icon-placement',
											'subset'=>[
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-sticky',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>singlePost>sticky',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Sticky:',												
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'singlePost',
															'checkvalue'=>$this->tl_sb_array['share_single_post_sticky_value'],
															'value'=>'None',
															'content_text'=>'None',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'singlePost',
															'checkvalue'=>$this->tl_sb_array['share_single_post_sticky_value'],
															'value'=>'Left',
															'content_text'=>'Left',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'singlePost',
															'checkvalue'=>$this->tl_sb_array['share_single_post_sticky_value'],
															'value'=>'Right',
															'content_text'=>'Right',
														],
													],
												],
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-standard',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>singlePost>standard',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Standerd:',												
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'singlePost',
															'checkvalue'=>$this->tl_sb_array['share_single_post_standard_value'],
															'value'=>'bottom',
															'content_text'=>'After the content',
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'singlePost',
															'checkvalue'=>$this->tl_sb_array['share_single_post_standard_value'],
															'value'=>'top',
															'content_text'=>'Before the content',
														],
													],
												],
											],
											],
										],
									],
								],
							],							
						]
					]);
					/*
					*for page selection
					*Added required fields and correspondence values
					*set function xxx to retrive all pages and thier ids
					*
					*/
					$this->addField([
						'title'=>'Visiblity in Page :',
						'type'=>'group-option',
						'groupLabel'=>'Click each of the other option while holding down the Ctrl key to select multiple page',
						'subset'=>[
							[
							'type'=>'right-block-option',			
							'wraper_class'=>'sb-block-value-block',
							'class'=>'tl-sb-icon-placement',
							'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>page>list',
							'subset'=>[
								[
									'type'=>'multiple-select',
									'class'=>'tl-sb-page tlsb-page',
									'value'=>$this->tl_sb_array['share_page_posts'],
									'options'=>$this->getPages(),
									'wraper_class'=>'',
									],
								],
							],
							[
								'type'=>'single-element',
								'wraper_class'=>'tl-sticky-option',
								'subset'=>[
									[
										'type'=>'single-element',
										'wraper_class'=>'sb-block-value-block',
										'subset'=>[
										[
											'title'=>'Placement',
											'type'=>'right-block-option',			
											'wraper_class'=>'tlsb-icon-placement',
											'subset'=>[
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-sticky',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>page>sticky',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Sticky:',												
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'page',
															'checkvalue'=>$this->tl_sb_array['share_page_pos_sticky_value'],
															'value'=>'None',
															'content_text'=>'None',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'page',
															'checkvalue'=>$this->tl_sb_array['share_page_pos_sticky_value'],
															'value'=>'Left',
															'content_text'=>'Left',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'page',
															'checkvalue'=>$this->tl_sb_array['share_page_pos_sticky_value'],
															'value'=>'Right',
															'content_text'=>'Right',
														],
													],
												],
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-standard',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>page>standard',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Standerd:',												
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'page',
															'checkvalue'=>$this->tl_sb_array['share_page_pos_standard_value'],
															'value'=>'bottom',
															'content_text'=>'After the content',
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'page',
															'checkvalue'=>$this->tl_sb_array['share_page_pos_standard_value'],
															'value'=>'top',
															'content_text'=>'Before the content',
														],
													],
												],
											],
											],
										],
									],
								],
							],							
						]
					]);
					/*
					*only for Blog Page
					*Added required fields and correspondence values
					*set function xxx to retrive all pages and thier ids
					*
					*/
					$this->addField([
						'title'=>'Visiblity in Blog Page :',
						'type'=>'group-option',
						'subset'=>[
							[
							'type'=>'right-block-option',			
							'wraper_class'=>'sb-block-value-block',
							'class'=>'tl-sb-icon-placement tlsb-blogpage',
							'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>blogPage>list',
							'subset'=>[
									[
									'type'=>'checkbox',
									'opt_cls'=>'placement-checkbox',
									'value'=>'postContent',
									'checkvalue'=>$this->tl_sb_array['share_blog_posts'],									
									'content_text'=>'Post List',									
									],
									[
									'type'=>'checkbox',
									'opt_cls'=>'placement-checkbox',
									'value'=>'pageContent',
									'checkvalue'=>$this->tl_sb_array['share_blog_posts'],									
									'content_text'=>'Blog page',									
									],
								],
							],
							[
								'type'=>'single-element',
								'wraper_class'=>'tl-sticky-option',
								'subset'=>[
									[
										'type'=>'single-element',
										'wraper_class'=>'sb-block-value-block',
										'subset'=>[
										[
											'title'=>'Placement',
											'type'=>'right-block-option',			
											'wraper_class'=>'tlsb-icon-placement',
											'subset'=>[
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-sticky',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>blogPage>sticky',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Sticky:',												
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'blogPage',
															'checkvalue'=>$this->tl_sb_array['share_blog_pos_sticky_value'],
															'value'=>'None',
															'content_text'=>'None',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'blogPage',
															'checkvalue'=>$this->tl_sb_array['share_blog_pos_sticky_value'],
															'value'=>'Left',
															'content_text'=>'Left',
														],
														[
															'type'=>'radiobutton',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'blogPage',
															'checkvalue'=>$this->tl_sb_array['share_blog_pos_sticky_value'],
															'value'=>'Right',
															'content_text'=>'Right',
														],
													],
												],
												[
													'type'=>'single-element',
													'wraper_class'=>'tlsb-share-standard',
													'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>blogPage>standard',
													'subset'=>[
														[
															'type'=>'single-element',							
															'class'=>'tlsb-icon-settings-sub-label',							
															'content_text'=>'Standerd:',												
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'blogPage',
															'checkvalue'=>$this->tl_sb_array['share_blog_pos_standard_value'],
															'value'=>'bottom',
															'content_text'=>'After the content',
														],
														[
															'type'=>'checkbox',							
															'opt_cls'=>'tlsb-icon-settings-sub-settings',							
															'name'=>'blogPage',
															'checkvalue'=>$this->tl_sb_array['share_blog_pos_standard_value'],
															'value'=>'top',
															'content_text'=>'Before the content',
														],
													],
												],
											],
											],
										],
									],
								],
							],							
						]
					]);
					$this->addField([
						'title'=>'Padding',
						'type'=>'number',
						'class'=>'tl-sb-icon-padding',
						'value'=>$this->share_icon_padding,
						'name'=>'tl-sb-icon-padding',
						'step'=>'.5',
						'noticetxt'=>'<b>px</b><span class = "tl-sb-message"><i>Notice: This change will appear only on the front-end</i></span>',
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>padding',
					]);
					$this->addField([
						'title'=>'Link Open',
						'type'=>'option',
						'class'=>'tl-sb-icon-link-open-tab',
						'value'=>$this->tl_sb_array[ "tl_sb_share_iconLinkOpen" ],
						'name'=>'tl-sb-icon-link-open-tab',
						'options'=>[
							'New tab'=>'New tab',
							'Same tab'=>'Same tab'
						],
						'path'=>'share>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>link_open_opt',
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
		<?php
		return ob_get_clean();
	}
	public function tableView(){
		ob_start();
		?>
		<div class = "tl-sb-data-row tl-sb-preview-share">
			
			<?php echo $this->viewContent(); ?>
				
		</div>
		<?php
		return ob_get_clean();
	}
	public function script(){}
	public function viewContent(){
		ob_start();
		?>
		<div class="tl-sb-innertab">
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
										<input type="text" class="tl-sb-the-shortcode" value="<?php echo esc_attr( tl_sb_shortCode( $idKey, 'share' ) ); ?>" readonly = "readonly">
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
												<a id="tl_sb_sgroup_edit" href="admin.php?page=tl-social-bucket&action=edit&id=<?php echo esc_attr( $idKey ); ?>&type=share" data-id="<?php echo esc_attr( $idKey ); ?>">
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
	private function getPosts(){
		$return=[];
		$args=[];
		$object='objects';
		$post_types = get_post_types($args, $object);
		$exclude = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'oembed_cache', 'user_request', 'wp_block', 'customize_changeset', 'page', 'shop_coupon', 'shop_order_refund', 'shop_order', 'product_variation', 'scheduled-action', 'wpcf7_contact_form'];
		foreach( $post_types as $type=>$props ):									
			if( !in_array( $props->name, $exclude ) ):
				$return[$props->name]=[$props->label, 'tlsb-icon-multi-select'];
			endif;
		endforeach;
		return $return;
	}
	private function getPages(){
		$return=[];
		$pages=get_pages();
		$frontpage_id = get_option( 'page_on_front' );
		$blog_id = get_option( 'page_for_posts' );
		foreach( $pages as $post ):									
			if( $post->ID != $frontpage_id && $post->ID != $blog_id ):
				$return[$post->ID]=[$post->post_title, 'tlsb-icon-multi-select'];
			endif;
		endforeach;
		return $return;
	}
}//end class

