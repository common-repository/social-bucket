<?php
if(!defined('ABSPATH')){
    exit;
   }
$tl_sb_query_var=[];
$tl_sb_action = isset( $_GET[ 'action' ] ) ? sanitize_text_field( $_GET[ 'action' ] ):null;
$tl_sb_type = isset( $_GET[ 'type' ] ) ? sanitize_text_field( $_GET[ 'type' ] ):'follow';
$tl_sb_query_var['action'] =$tl_sb_action;
$tl_sb_query_var['id'] =isset( $_GET[ 'id' ] ) ? sanitize_text_field( $_GET[ 'id' ] ):'';
$tl_sb_query_var['type'] =$tl_sb_type;

class TLSB_AdminForm{
	
	private $tl_sb_query_var=[];
	private $tab_content='';
	private $table_content='';
	private $detail_content='';
	private $tl_social_bucket_array;
	public $on_edit_script = null;
	public function __construct($tl_sb_query_var=[]){
		$this->checkOption();
		$this->tl_sb_query_var=$tl_sb_query_var;
		$this->tl_social_bucket_array = get_option( 'tl_sb_settings', [] );		
		if( is_string( $this->tl_social_bucket_array ) )$this->tl_social_bucket_array = [] ;
	}//end of Constructor
	
	public function listTab(){
		$this->tabListHtml(new TLSB_Follow( $this->tl_social_bucket_array['follow'], $this->tl_sb_query_var ) );		
		$this->tabListHtml(new TLSB_Share( $this->tl_social_bucket_array['share'], $this->tl_sb_query_var ) );		
		$this->tabListHtml(new TLSB_Review( $this->tl_social_bucket_array['review'], $this->tl_sb_query_var ) );		
		$this->tabListHtml(new TLSB_Comment( $this->tl_social_bucket_array['comment'], $this->tl_sb_query_var ) );		
	}//end of listTab
	private function tabListHtml( ServiceType $service_name ){
		$this->service_name=$service_name;
		$this->tab_content .= $this->service_name->tabList();
		$this->table();
		//$this->tabDetailHtml();
	}//end of tabListHtml
	private function tabDetailHtml(){
		$this->detail_content .= $this->service_name->tabContent();
	}//end of tabDetailHtml
	private function hiddenTag($args=[]){
		$prevValue = !empty( $args ) && count( $args )>0 ? json_encode( $args ) : '';

		$prevValue = stripslashes_deep($prevValue);
		ob_start();
			?>
		<input type = "hidden" id = "tl-prev-obj-value" value = '<?php echo $prevValue; ?>'>
		<?php
		return ob_get_clean();
	}
	private function table(){
		$this->table_content .= $this->service_name->tableView();
	}
	public function footer(){
		ob_start();
		?>
		<div class = "tl-sb-table">
		<?php echo $this->table_content; ?>
		</div>
		<?php echo $this->animationEffect(); ?>
		
	<?php
	}//end of footer
	public function outputHtml(){
		ob_start();
		?>		
		<div class = "tl-social-bucket-wrapper">
			<div class = "tl-sb-row tl-sb-social-type">				
				<?php
					echo $this->hiddenTag($this->tl_social_bucket_array);
					echo $this->tab_content;
				?>
			</div>
			<div class = "tl-sb-form" style = "">
				<?php
					echo $this->detail_content;
				?>
			</div>
			<div class = "tl-sb-icon-data" style = "display:none ;"></div>	
		</div>	
		<?php
		$this->footer();
	}//end of outputHtml
	function animationEffect(){
		ob_start();
		?>
		<div class="save-msg">
			<div class="message">
			  <div class="check"><i class="fas fa-check"></i></div>
			  <p>Success</p>
			  <p>The social data has been saved</p>
			</div>
		</div>
		<div class="delete-msg">
			<div class="delete-message">
			  <div class="delete-check"><i class="fas fa-check"></i></div>
			  <p>Deleted</p>
			  <p>The social data Deleted Successfully</p>
			</div>
		</div>
		<div class="setting-animation">
			<div class="load">
				<div class="gear one">
					<svg id="blue" viewbox="0 0 100 100" fill="#94DDFF">
						<path d="M97.6,55.7V44.3l-13.6-2.9c-0.8-3.3-2.1-6.4-3.9-9.3l7.6-11.7l-8-8L67.9,20c-2.9-1.7-6-3.1-9.3-3.9L55.7,2.4H44.3l-2.9,13.6      c-3.3,0.8-6.4,2.1-9.3,3.9l-11.7-7.6l-8,8L20,32.1c-1.7,2.9-3.1,6-3.9,9.3L2.4,44.3v11.4l13.6,2.9c0.8,3.3,2.1,6.4,3.9,9.3      l-7.6,11.7l8,8L32.1,80c2.9,1.7,6,3.1,9.3,3.9l2.9,13.6h11.4l2.9-13.6c3.3-0.8,6.4-2.1,9.3-3.9l11.7,7.6l8-8L80,67.9      c1.7-2.9,3.1-6,3.9-9.3L97.6,55.7z M50,65.6c-8.7,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6s15.6,7,15.6,15.6S58.7,65.6,50,65.6z"></path>
					</svg>
				</div>
				<div class="gear two">
					<svg id="pink" viewbox="0 0 100 100" fill="#FB8BB9">
						<path d="M97.6,55.7V44.3l-13.6-2.9c-0.8-3.3-2.1-6.4-3.9-9.3l7.6-11.7l-8-8L67.9,20c-2.9-1.7-6-3.1-9.3-3.9L55.7,2.4H44.3l-2.9,13.6      c-3.3,0.8-6.4,2.1-9.3,3.9l-11.7-7.6l-8,8L20,32.1c-1.7,2.9-3.1,6-3.9,9.3L2.4,44.3v11.4l13.6,2.9c0.8,3.3,2.1,6.4,3.9,9.3      l-7.6,11.7l8,8L32.1,80c2.9,1.7,6,3.1,9.3,3.9l2.9,13.6h11.4l2.9-13.6c3.3-0.8,6.4-2.1,9.3-3.9l11.7,7.6l8-8L80,67.9      c1.7-2.9,3.1-6,3.9-9.3L97.6,55.7z M50,65.6c-8.7,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6s15.6,7,15.6,15.6S58.7,65.6,50,65.6z"></path>
					</svg>
				</div>
				<div class="gear three">
					<svg id="yellow" viewbox="0 0 100 100" fill="#FFCD5C">
						<path d="M97.6,55.7V44.3l-13.6-2.9c-0.8-3.3-2.1-6.4-3.9-9.3l7.6-11.7l-8-8L67.9,20c-2.9-1.7-6-3.1-9.3-3.9L55.7,2.4H44.3l-2.9,13.6      c-3.3,0.8-6.4,2.1-9.3,3.9l-11.7-7.6l-8,8L20,32.1c-1.7,2.9-3.1,6-3.9,9.3L2.4,44.3v11.4l13.6,2.9c0.8,3.3,2.1,6.4,3.9,9.3      l-7.6,11.7l8,8L32.1,80c2.9,1.7,6,3.1,9.3,3.9l2.9,13.6h11.4l2.9-13.6c3.3-0.8,6.4-2.1,9.3-3.9l11.7,7.6l8-8L80,67.9      c1.7-2.9,3.1-6,3.9-9.3L97.6,55.7z M50,65.6c-8.7,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6s15.6,7,15.6,15.6S58.7,65.6,50,65.6z"></path>
					</svg>
				</div>		
			</div>
			<div class="text">loading...</div>
		</div>
		
		<div class="tl-loader-wrapp tlsb-add-new-animation">
			<div class="tl-spinner">
				<div class="spinner-items"></div>
				<div class="spinner-items"></div>
				<div class="spinner-items"></div>
				<div class="spinner-items"></div>
				<div class="spinner-items"></div>
			</div>
		</div>
		
		<!--<div class="tlsb-add-new-animation">
			<svg version="1.1" id="L1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
					<circle fill="none" stroke="#ff3fd8" stroke-width="6" stroke-miterlimit="15" stroke-dasharray="14.2472,14.2472" cx="50" cy="50" r="47" >
						<animateTransform 
							 attributeName="transform" 
							 attributeType="XML" 
							 type="rotate"
							 dur="5s" 
							 from="0 50 50"
							 to="360 50 50" 
							 repeatCount="indefinite" />
				</circle>
				<circle fill="none" stroke="#26f01f" stroke-width="1" stroke-miterlimit="10" stroke-dasharray="10,10" cx="50" cy="50" r="39">
						<animateTransform 
							 attributeName="transform" 
							 attributeType="XML" 
							 type="rotate"
							 dur="5s" 
							 from="0 50 50"
							 to="-360 50 50" 
							 repeatCount="indefinite" />
				</circle>
				<g fill="#00ffff">
				<rect x="30" y="35" width="5" height="30">
					<animateTransform 
						 attributeName="transform" 
						 dur="1s" 
						 type="translate" 
						 values="0 5 ; 0 -5; 0 5" 
						 repeatCount="indefinite" 
						 begin="0.1"/>
				</rect>
				<rect x="40" y="35" width="5" height="30" >
					<animateTransform 
						 attributeName="transform" 
						 dur="1s" 
						 type="translate" 
						 values="0 5 ; 0 -5; 0 5" 
						 repeatCount="indefinite" 
						 begin="0.2"/>
				</rect>
				<rect x="50" y="35" width="5" height="30" >
					<animateTransform 
						 attributeName="transform" 
						 dur="1s" 
						 type="translate" 
						 values="0 5 ; 0 -5; 0 5" 
						 repeatCount="indefinite" 
						 begin="0.3"/>
				</rect>
				<rect x="60" y="35" width="5" height="30" >
					<animateTransform 
						 attributeName="transform" 
						 dur="1s" 
						 type="translate" 
						 values="0 5 ; 0 -5; 0 5"  
						 repeatCount="indefinite" 
						 begin="0.4"/>
				</rect>
				<rect x="70" y="35" width="5" height="30" >
					<animateTransform 
						 attributeName="transform" 
						 dur="1s" 
						 type="translate" 
						 values="0 5 ; 0 -5; 0 5" 
						 repeatCount="indefinite" 
						 begin="0.5"/>
				</rect>
				</g>
			</svg>
		</div> -->
		
		<?php
		return ob_get_clean();
	}//end of animationEffect
	function checkOption(){
		$myvar = [ "follow" => [" "], "share" => [" "], "review" => [" "] ,"comment" => [" "] ];
		$prev_options = get_option("tl_sb_settings");
		if( empty( $prev_options ) ) {
			update_option('tl_sb_settings', $myvar);
		}else{
			$prev_key	=	array_keys( $prev_options );
			$current_key	=	array_keys( $myvar );
			if( count( $current_key ) !== count( $prev_key ) ){
				for($i=0; $i<count($current_key); $i++){
					if(!in_array($current_key[$i], $prev_key)){
						$prev_options[$current_key[$i]]=[" "];
					}
				}
				update_option('tl_sb_settings', $prev_options);
			}
		}
	}
}//end of class


if(class_exists('TLSB_AdminForm')){
	$tlsb_admin_form_obj= new TLSB_AdminForm($tl_sb_query_var);
	$tlsb_admin_form_obj->listTab();
	$tlsb_admin_form_obj->outputHtml();
}
