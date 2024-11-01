<?php

class TLSB_CommentView{
	
	public $data			=	[];
	public $comment_options 	=	[];
	public $html 			=	'';
	public $chekstatus;
	public $shareId;
	public $compose 		=	[];
	public $placement		=	[];
	
	public function init_code(){
		add_filter( 'the_content', array( $this, 'place_comments' ), 40 );
		add_action( 'wp_body_open', array( $this, 'social_comment_script' ) );
		add_action( 'login_header', array( $this, 'social_login_script' ) );
		add_action( 'login_form', array( $this, 'social_buttons' ) );
	
		$data = get_option('tl_sb_settings',[]);
		if( is_string( $data ) ) $data = [];
		$this->comment_options = isset( $data[ 'comment' ] ) && count( $data[ 'comment' ] ) ? $data[ 'comment' ] : [];
		
		if(isset($data[ 'comment' ]) && $data[ 'comment' ] != [" "]) {
			add_filter('comments_open', array( $this,'df_disable_comments_status'), 20, 2);
			add_filter('pings_open', array( $this,'df_disable_comments_status'), 20, 2);
			add_filter('comments_array', array($this, 'df_disable_comments_hide_existing_comments'), 10, 2);
		}
		
		if( count( $this->comment_options ) >0 ){
			foreach( $this->comment_options as $id =>$innerCode ){
				$position		=	[];
				$this->shareId 	= 	$id;
				
			
				$placement=[];
				if(isset($innerCode['settings']['placement'])){
					$this->placement	=	$innerCode['settings']['placement'];
				}
				
			}
		}	
	}
	
	// Close comments on the front-end
	public function df_disable_comments_status() {
		return false;
	}
	
	// Hide existing comments
	function df_disable_comments_hide_existing_comments($comments) {
		$comments = array();
		//print_r($comments);
		return $comments;
	}


	public function social_buttons() {
		echo '<p><div class="fb-login-button">Login with Facebook</div></p>';
	}
	
	
	
	public function display( $id, $sticky=null ){
		if( isset( $this->comment_options[ $id ] ) && count( $this->comment_options[ $id ] ) >0 ){
			$inner_data			=	$this->comment_options[ $id ];
			$icon_name			=	isset( $inner_data['settings']['name'] ) ? $inner_data['settings']['name'] : '';
			$post_count			=	isset( $inner_data['settings']['count'] ) ? $inner_data['settings']['count'] : '2';
			$html = '';
			//echo "<pre>"; print_r($this->comment_options[ $id ]); die;
			
			$html .= '<div class = "tl-sb-preview-area tl-sb-stickypos-inline"><div class="tl-sb-title"><h4>'.$icon_name.'</h4></div><div class="tl-sb-icons">';
			$html .= '<div class="fb-comments" data-href="' . get_permalink() .'" data-width="100%" data-numposts="' . $post_count . '" data-order-by="social" data-colorscheme="light"></div>';
			$html .= '</div>';
			return $html;			
		}
	}
	
	
	public function place_comments($content){
		//For Single post
		if ( is_single() ){			
			if(!empty($this->placement['singlePost']['list'])){
				/*$current_type=get_post_type();
				if(in_array($current_type, $this->placement['singlePost']['list'])){
					$post_id = get_the_id();
					$wp_comments = get_comments(array('post_id' => $post_id));
					$comment_count = count($wp_comments);
					//print_r($wp_comments); die;
					$content .= '<div class="tabs">
									<ul class="tab-links">
										<li class="active"><a href="#tab1"> Wordpress Comment</a></li>';
									$i = 2;
									foreach ($this->comment_options[1][icons] as $icons) {
										$content .= '<li><a href="#tab' . $i++ . '">' . $icons['label'] . '</a></li>';
									}
										
					$content .= 	'</ul>' . comment_form(array("title_reply" => "Leave Comment"), $post_id) .
									
									'<div class="tab-content">
										<div id="tab1" class="tab active">
											<div id="comments" class="comments-area">
												<h2 class="comments-title">' . $comment_count . ' Replies to "' . get_the_title() . '"</h2>
												<ol>';
												foreach($wp_comments as $wpc) { 
					$content .= 					'<li>' . $wpc->comment_content . '</li>';
												}
					$content .= 				'</ol>
										</div>';
										$i = 2;
										foreach ($this->comment_options[1][icons] as $icons) {
											$content .= '<div id="tab' . $i++ . '" class="tab">' . $this->display( $this->shareId ) . '</div>';
										}
										
					$content .= 	'</div>
								</div>';
				}*/
				
				$current_type=get_post_type();
				if(in_array($current_type, $this->placement['singlePost']['list'])){
					$content = $content . $this->display( $this->shareId );
				}
			}
		}
		//if( is_archive() || is_author() || is_category() || is_tag() || is_home() || is_front_page() ){
		if( is_home() || is_front_page() ){
			if(isset($this->placement['blogPage']['list']) && in_array('postContent', $this->placement['blogPage']['list'])){
				$content = $content . $this->display( $this->shareId );
			}
			/*if(isset($this->placement['blogPage']['list']) && in_array('pageContent', $this->placement['blogPage']['list'])){
				add_action( 'wp_footer', array( $this, 'place_into_blog_page' ) );
			}*/
		}
		return $content;
	}
	public function place_into_blog_page(){
		echo ($this->display( $this->shareId));
	}
	
	public function social_comment_script(){
        if( count( $this->comment_options ) >1 ) {
			$html = '<div id="fb-root"></div> 
				<script>(function(d, s, id) { 
				var js, fjs = d.getElementsByTagName(s)[0]; 
				if (d.getElementById(id)) return; 
				js = d.createElement(s); js.id = id; 
				js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; 
				fjs.parentNode.insertBefore(js, fjs); 
				}(document, "script", "facebook-jssdk"));
				
				jQuery(document).ready(function() {
					jQuery(".tabs .tab-links a").on("click", function(e) {
						var currentAttrValue = jQuery(this).attr("href");

						// Show/Hide Tabs
						jQuery(".tabs " + currentAttrValue).show().siblings().hide();

						// Change/remove current tab to active
						jQuery(this).parent("li").addClass("active").siblings().removeClass("active");

						e.preventDefault();
					});
				});
				</script>';
			echo $html;
		}
	}
	
	public function social_login_script() {
		$html = '<div id="fb-root"></div> 
				<script>
					window.fbAsyncInit = function() {
					  FB.init({
					appId      : "2116674555291203", // App ID
					status     : true, // check login status
					cookie     : true, // enable cookies to allow the server to access the session
					xfbml      : true  // parse XFBML
				  });
				};
				
				(function(d){
				   var js, id = "facebook-jssdk", ref = d.getElementsByTagName("script")[0];
				   if (d.getElementById(id)) {return;}
				   js = d.createElement("script"); js.id = id; js.async = true;
				   js.src = "//connect.facebook.net/en_US/all.js";
				   ref.parentNode.insertBefore(js, ref);
				 }(document));
			  </script>';
		echo $html;
	}
}
$tlsb_comment_view = new TLSB_CommentView();
$tlsb_comment_view->init_code();
