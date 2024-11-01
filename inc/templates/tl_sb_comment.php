<?php
if(!defined('ABSPATH')){
    exit;
   }
class TLSB_Comment extends Controller implements ServiceType{
	public $tlsb_unique_id;
	public $active_list_icons;
	private $tl_sb_array=[];
	private $dynamic_array=[];
	private $follow_icon_size, $follow_wrapper_class, $follow_icon_padding, $tl_social_bucket_array, $settings_area=false;
	public function __construct($tl_sb_array=[], $tl_sb_query_var=''){
		//pr($tl_sb_array, true);
		$this->dynamic_array=!empty($tl_sb_array)?$tl_sb_array:[];
		$this->tl_sb_array[ "tl_SB_theId" ] = !empty($tl_sb_array) && !empty($tl_sb_query_var['id'])? sanitize_text_field($tl_sb_query_var[ 'id' ] ):1;
		$this->tl_sb_array[ "active" ] = isset($tl_sb_array[1]) ? true : false;
		$this->icons=[ 'facebook' ];
		$this->tl_sb_array[ "tl_sb_comment_block" ] = sanitize_text_field( 'active' );
		$this->tl_sb_array['comment_count'] = !empty($tl_sb_array["tl_sb_comment_count"])?$tl_sb_array["tl_sb_comment_count"]:2;
		$this->tl_sb_array['comment_single_posts'] =	!empty($tl_sb_array[ "tl_sb_comment_iconPlacement" ]['singlePost']['list'])?$tl_sb_array[ "tl_sb_comment_iconPlacement" ]['singlePost']['list']:[];
		$this->tl_sb_array['share_blog_posts'] =	!empty($tl_sb_array[ "tl_sb_comment_iconPlacement" ]['blogPage']['list'])?$tl_sb_array[ "tl_sb_comment_iconPlacement" ]['blogPage']['list']:[];
		$this->tl_sb_array[ "tl_sb_comment_previewAreaContent" ]='';
		$tl_sb_idArray = $this->tl_sb_array[ "active" ]?$tl_sb_array[ $this->tl_sb_array[ "tl_SB_theId" ] ]:'';
		if( !empty( $tl_sb_idArray[ 'icons' ] ) ){
			$this->activeIcons($tl_sb_idArray[ 'icons' ]);
			foreach( $tl_sb_idArray[ 'icons' ] as $iconkey =>$icon ){
				$this->tl_sb_array[ "tl_sb_comment_previewAreaContent" ] .=  '<div class="tl-sb-icon-head" id="tl_sb_fe_'.$iconkey.'" data-name="'.$iconkey.'" data-save="comment>'.$this->tl_sb_array[ "tl_SB_theId" ].'>icons>'.$iconkey.'>content"><div class="tl-sb-icon-wrapper">'.stripslashes_deep( $icon[ 'content' ] ).'</div>';
				$tl_sb_pAIcon[ $iconkey ] = 'active';
			}			
		}
		
		$this->tlsb_unique_id=$this->tl_sb_array[ "tl_SB_theId" ];
	}
	public function tabList(){
		ob_start();
		?>
		<span class = "tl-sb-social-text" data-name = "comment" data-type = "tl-sb-comment"  data-id = "<?php echo (!empty($this->tl_sb_array[ "tl_SB_theId" ])?esc_attr( $this->tl_sb_array[ "tl_SB_theId" ] ):'');?>"><i class="fas fa-comment"></i> Comment</span>
		<?php
		return ob_get_clean();
	}
	private function activeIcons($activeIcons=[]){
		$this->active_icons=array_keys($activeIcons);
	}
	public function tabContent(){
		ob_start();
		?>		
		<div class = "tl-sb-socialtype tl-sb-comment active <?php echo $this->share_wrapper_class; ?>" data-title = "comment" data-name = "tl-sb-comment">
			<div class = "tl-sb-comment-form" <?php echo $this->tl_sb_array[ "tl_sb_comment_block" ]; ?>>
				<div class = "tl-sb-form-general">
					<div class = "tl-sb-button-div">
						<p class="tl-sb-button-title">Click on button to add or remove icon</p>
						<?php
							echo $this->iconButton($this->icons);
						?>
					</div>
					<div class="tl-sb-preview-area atl-sb-preview-area" data-save="comment><?php echo esc_attr( $this->tl_sb_array["tl_SB_theId"] );?>>icons">
						<h3><i class="far fa-eye"></i> Preview Area</h3>
						<?php echo isset( $this->tl_sb_array["tl_sb_comment_previewAreaContent"] )? $this->tl_sb_array["tl_sb_comment_previewAreaContent"]:''; ?>
					</div>			
				</div>
				<?php echo $this->settingsArea($this->settings_area); ?>
				<div class = "tl-sb-form-settings-general">
					<?php
					$this->addField([
						'title'=>'Section Title ',
						'type'=>'text',
						'name'=>'tl-sb-follow-group-name',
						'class'=>'tl-sb-follow-group-name',
						'id'=>'',
						'path'=>'comment>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>name',
						'value'=>'',//$this->tl_sb_array[ "tl_sb_share_socialName" ],
					]);
					$this->addField([
						'title'=>'No. of Comments',
						'type'=>'comboslider',
						'class'=>'tl-sb-comment_count',
						'value'=>$this->tl_sb_array['comment_count'],
						'name'=>'tl-sb-comment_count',
						'min'=>1,
						'max'=>10,
						'step'=>1,
						'path'=>'comment>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>size',					
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
							'path'=>'comment>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>singlePost>list',
							'subset'=>[
								[
									'type'=>'multiple-select',
									'class'=>'tl-sb-single-post tlsb-page',
									'value'=>$this->tl_sb_array['comment_single_posts'],
									'options'=>$this->getPosts(),
									'wraper_class'=>'',
									],
								],
							],				
						]
					]);
					/*
					*only for Blog Page
					*Added required fields and correspondence values
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
							'path'=>'comment>'.$this->tl_sb_array[ "tl_SB_theId" ].'>settings>placement>blogPage>list',
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
						]
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
	public function script(){}
	public function tableView(){
		ob_start();
		?>
		<div class = "tl-sb-data-row tl-sb-preview-comment">
			
			<?php echo $this->viewContent(); ?>
				
		</div>
		<?php
		return ob_get_clean();
	}
	public function viewContent(){
		ob_start();
		?>
		<div class = "tl-sb-innertab">
			<div class="tl-sb-data-inner-row">
				<div class="tl-sb-enable-comment-block">
						<div class="tl-sb-comment-control">
							<p class="placement-checkbox"><input type="checkbox" class="tl-sb-comment-enable" value = "true"  data-save="comment>1>activate">Enable Social Comment</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	private function getPosts(){
		$return=[];
		$args=[];
		$object='objects';
		$post_types = get_post_types($args, $object);
		$exclude = ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'oembed_cache', 'user_request', 'wp_block', 'customize_changeset', 'page', 'shop_coupon', 'shop_order_refund', 'shop_order', 'product_variation', 'scheduled-action', 'wpcf7_contact_form'];
		foreach( $post_types as $type=>$props ):									
			if( !in_array( $props->name, $exclude ) ):
				$return[$props->label]=[$props->label, 'tlsb-icon-multi-select'];
			endif;
		endforeach;
		return $return;
	}
}