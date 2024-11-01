<?php
if(!defined('ABSPATH')){
    exit;
   }
class TLSB_ShareView{
	
	public $data			=	[];
	public $share_options 	=	[];
	public $html 			=	'';
	public $chekstatus;
	public $shareId;
	public $compose 		=	[];
	public $placement		=	[];
	
	public function init_code(){
		add_filter( 'the_content', array( $this, 'place_share_socials' ), 4000 );
		add_action( 'wp_head', array( $this, 'generateMeta' ) );
		
		$data = get_option('tl_sb_settings',[]);
		if( is_string( $data ) ) $data = [];
		$this->share_options = isset( $data[ 'share' ] ) && count( $data[ 'share' ] ) ? $data[ 'share' ] : [];
		
		if( count( $this->share_options ) >0 ){
			foreach( $this->share_options as $id =>$innerCode ){
				$position		=	[];
				$this->shareId 	= 	$id;
				$placement=[];
				if(isset($innerCode['settings']['placement'])){
					$this->placement	=	$innerCode['settings']['placement'];
				}				
			}
		}	
	}
	
	public function display( $id, $sticky=null ){
		if( isset( $this->share_options[ $id ] ) && count( $this->share_options[ $id ] ) >0 ){
			$inner_data			=	$this->share_options[ $id ];
			$link_target		=	isset( $inner_data[ 'settings' ][ 'link_open_opt' ]) ? $inner_data[ 'settings' ][ 'link_open_opt' ] : '_self';
			$padding			=	isset( $inner_data[ 'settings' ][ 'padding' ] ) ? 'style = "padding:'.$inner_data[ 'settings' ][ 'padding' ].'px"' : '';
			$icon_name			=	isset( $inner_data['settings']['name'] ) ? $inner_data['settings']['name'] : '';
			$html				=	'';
			if( isset($sticky) ){
				$html .= '<div class = "tl-sb-preview-area tl-sb-stickypos-'.$sticky.'"><div class="tl-sb-title"></div><div class="tl-sb-icons">';
			}else{
				$html .= '<div class = "tl-sb-preview-area tl-sb-stickypos-inline"><div class="tl-sb-title"><h4>'.$icon_name.'</h4></div><div class="tl-sb-icons">';
			}
				
			$uri = isset( $inner_data[ 'settings' ][ 'slug' ]) ? $inner_data[ 'settings' ][ 'slug' ] : 'postslug';   //get_the_permalink() ;
			$uri = $this->getSlug($uri, $id);
			if( isset( $inner_data[ 'settings' ][ 'ordering' ] ) && count( $inner_data[ 'settings' ][ 'ordering' ] ) > 0 ){					
				foreach( $inner_data[ 'settings' ][ 'ordering' ] as $iconkey ){
					$arr[ 'iconDefault' ] = isset( $inner_data[ 'icons' ][ $iconkey ][ 'color' ][ 'bydefault' ] ) ? $inner_data[ 'icons' ][ $iconkey ][ 'color' ][ 'bydefault' ] : tlsb_generateIconColor( $iconkey, 'default' );
					$arr[ 'iconHover' ] = isset( $inner_data[ 'icons' ][ $iconkey ][ 'color' ][ 'hover' ] ) ? $inner_data[ 'icons' ][ $iconkey ][ 'color' ][ 'hover' ]:tlsb_generateIconColor( $iconkey, 'hover' );
					$arr[ 'bgDefault' ] = isset( $inner_data[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'bydefault' ]) ? $inner_data[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'bydefault' ] : tlsb_generateColor( $iconkey, 'default' );
					$arr[ 'bgHover' ] = isset( $inner_data[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'hover' ]) ? $inner_data[ 'icons' ][ $iconkey ][ 'bgcolor' ][ 'hover' ] : tlsb_generateColor( $iconkey, 'hover' );
					$url = esc_url( tlsb_getUrlContent( $iconkey, $uri ) );
					$html .=  '<div class="tl-sb-icon-head" data-name="'. $iconkey .'"><div class="tl-sb-icon-wrapper"><a href = "'.$url.'" target = "'.esc_attr( $link_target ).'" '. $padding .'>'.stripslashes_deep( $inner_data[ 'icons' ][ $iconkey ][ 'content' ] ).'</a><div class="tlsb-tooltip">'. $iconkey .'</div><div class = "tl-sb-icon-individual-data" style = "display:none ;">'.json_encode( $arr ).'</div></div></div>';
				}
			}
			
			
			$html .= '</div></div>';

			
			return $html;			
		}
	}
	
	
	public function place_share_socials($content){
		//For Single post
		
		if ( is_single() ){
			if(!empty($this->placement['singlePost']['list'])){
				$current_type=get_post_type();
				if(in_array($current_type, $this->placement['singlePost']['list'])){
					if(isset($this->placement['singlePost']['standard']) && in_array('top', $this->placement['singlePost']['standard'])){
						$content = $this->display( $this->shareId ).$content;
					}
					if(isset($this->placement['singlePost']['standard']) && in_array('bottom', $this->placement['singlePost']['standard'])){
						$content = $content . $this->display( $this->shareId );
					}
					if( isset( $this->placement['singlePost']['sticky']) && 'None' != $this->placement['singlePost']['sticky'] ){
						$content = $content . $this->display( $this->shareId, $this->placement['singlePost']['sticky'] );
					}
				}
			}
		}
		if ( is_page() ){			
			if(!empty($this->placement['page']['list'])){				
				$current_type=get_the_ID();
				if(in_array($current_type, $this->placement['page']['list'])){
					if(!empty($this->placement['page']['standard']) && in_array('top', $this->placement['page']['standard'])){
						$content = $this->display( $this->shareId ).$content;
					}
					if(isset($this->placement['page']['standard']) && in_array('bottom', $this->placement['page']['standard'])){
						$content = $content . $this->display( $this->shareId );
					}
					if( isset( $this->placement['page']['sticky'] ) && 'None' != $this->placement['page']['sticky'] ){
						$content = $content . $this->display( $this->shareId, $this->placement['page']['sticky'] );
					}
				}
			}
		}
		//if( is_archive() || is_author() || is_category() || is_tag() || is_home() || is_front_page() ){
		if( is_home() || is_front_page() ){
			if(isset($this->placement['blogPage']['list']) && in_array('postContent', $this->placement['blogPage']['list'])){
				if(isset($this->placement['blogPage']['standard']) && in_array('top', $this->placement['blogPage']['standard'])){
					$content = $this->display( $this->shareId ).$content;
				}
				if(isset($this->placement['blogPage']['standard']) && in_array('bottom', $this->placement['blogPage']['standard'])){
					$content = $content . $this->display( $this->shareId );
				}
			}
			if(isset($this->placement['blogPage']['list']) && in_array('pageContent', $this->placement['blogPage']['list'])){
				add_action( 'wp_footer', array( $this, 'place_into_blog_page' ) );
			}
		}
		return $content;
	}
	public function place_into_blog_page(){
		if( isset( $this->placement['blogPage']['sticky'] ) && 'None' != $this->placement['blogPage']['sticky'] ){
			echo ($this->display( $this->shareId, $this->placement['blogPage']['sticky'] ));
		}
	}
	
	public function getSlug($prevurl, $id){
		$slug;
		if($prevurl == 'homepage'){
			$slug = urlencode(get_home_url());
		}else if($prevurl == 'postslug'){
			$slug = urlencode(get_permalink());
		}else if($prevurl == 'customslug'){
			$slug = urlencode($this->share_options[ $id ][ 'customSlug' ]);
		}else{
			$slug = '';
		}
		return $slug;
	}
	public function generateMeta(){
        if( count( $this->share_options ) >1 ) {
			$post_id  =  get_queried_object_id();
			$post_obj  =  get_post( $post_id );
			$content  =  isset($post_obj)? wp_strip_all_tags(do_shortcode( $post_obj->post_content ) ):'';
			$getTitle = get_the_title();
			$image = has_post_thumbnail() ? get_the_post_thumbnail_url() : '';
			$html = '' ;
			$html.= '<meta property = "og:locale" content = "en_US" />';
			$html.= '<meta property = "og:title" content = "'.$getTitle.'" />';
			$html.= '<meta property = "og:type" content = "website" />';
			$html.= '<meta property = "og:url" content = "'.urlencode(get_permalink()).'" />';
			$html.= '<meta property = "og:image" content = "'.$image.'" />';
			$html.= '<meta property = "og:image:width" content = "400" />';
			$html.= '<meta property = "og:image:height" content = "300" />';
			$html.= '<meta property = "og:site-name" content = "'.get_bloginfo('name').'" />';
			$html.= '<meta property = "og:description" content = "'.$content.'" />';
			$html.= '<script>
					!function(f,b,e,v,n,t,s)
					{if(f.fbq)return ;n = f.fbq = function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};
					if(!f._fbq)f._fbq = n ;n.push = n ;n.loaded = !0 ;n.version = "2.0";
					n.queue = [] ;t = b.createElement(e) ;t.async = !0;
					t.src = v ;s = b.getElementsByTagName(e)[ 0 ];
					s.parentNode.insertBefore(t,s)}(window,document,"script",
					"https://connect.facebook.net/en_US/fbevents.js");
					fbq("track", "PageView");
					</script>';
			echo $html;
		}
	}
}
$tlsb_share = new TLSB_ShareView();
$tlsb_share->init_code();
