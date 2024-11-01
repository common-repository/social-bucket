<?php
if(!defined('ABSPATH')){
    exit;
  }
//ajax call
add_action('wp_ajax_tl_sb_ajax_cb_action', 'tl_sb_ajax_cb_action');
function tl_sb_ajax_cb_action(){
	$myvar = [ "follow" => [" "], "share" => [" "], "review" =>[" "], "comment" => [" "] ];
	$myvar=(!empty($_POST['social_data']))?$_POST['social_data']:$myvar;
	update_option('tl_sb_settings', $myvar);
	$tl_social_bucket_array = get_option( 'tl_sb_settings', [] );
	$type=!empty($_POST['type'])?$_POST['type']:'follow';
	$tl_sb_query_var=[];
	$detail_content=[];
	$tl_sb_query_var['type'] =$type;
	$dyn_class_name='TLSB_'.strtoupper($type[0]).substr($type, 1, strlen($type));
	$add_new_obj=new $dyn_class_name( $tl_social_bucket_array[ $type ], $tl_sb_query_var );
	$detail_content['html']=$add_new_obj->viewContent();
	$detail_content['json']=stripslashes_deep($tl_social_bucket_array);
	//pr($detail_content, true);
	echo json_encode($detail_content);
	wp_die();
}

/*  ---- Comment Disable ---- */
add_action('wp_ajax_tl_sb_comment_disable_action', 'tl_sb_comment_disable_action');
function tl_sb_comment_disable_action(){
	$myvar = [ "follow" => [" "], "share" => [" "], "review" =>[" "], "comment" => [" "] ];
	$myvar=(!empty($_POST['social_data']))?$_POST['social_data']:$myvar;
	//update_option('tl_sb_settings', $myvar);
	$tl_social_bucket_array = get_option( 'tl_sb_settings', [] );
	$type=!empty($_POST['type'])?$_POST['type']:'comment';
	$tl_sb_query_var=[];
	$detail_content=[];
	$tl_sb_query_var['type'] =$type;
	$tl_sb_query_var['id'] =1;
	$dyn_class_name='TLSB_'.strtoupper($type[0]).substr($type, 1, strlen($type));
	$add_new_obj=new $dyn_class_name( $tl_social_bucket_array[ $type ], $tl_sb_query_var );
	$detail_content['body']=$add_new_obj->tabContent();
	$detail_content['table']=$add_new_obj->viewContent();
	$detail_content['json']=stripslashes_deep($tl_social_bucket_array);
	//pr($detail_content, true);
	echo json_encode($detail_content);
	wp_die();
}
// delete a group
add_action('wp_ajax_tl_sb_ajax_cb_delete', 'tl_sb_ajax_cb_delete');
function tl_sb_ajax_cb_delete(){
	$myvar			=	sanitize_text_field($_POST['social_id']);
	$socialType		=	sanitize_text_field($_POST['socialType']);
	$tl_sb_fromDb	=	get_option('tl_sb_settings',[]);
	if(isset($tl_sb_fromDb) && count($tl_sb_fromDb)>0){
		$tl_sb_current_array=$tl_sb_fromDb[$socialType];
		unset($tl_sb_current_array[$myvar]);
		$tl_sb_fromDb[$socialType]=$tl_sb_current_array;
		update_option('tl_sb_settings', $tl_sb_fromDb);		
	}
	$type=!empty(socialType)?$socialType:'follow';	
	$tl_social_bucket_array = get_option( 'tl_sb_settings', [] );
	$tl_sb_query_var=[];
	$detail_content=[];	
	$tl_sb_query_var['type'] =$type;
	$dyn_class_name='TLSB_'.strtoupper($type[0]).substr($type, 1, strlen($type));
	$add_new_obj=new $dyn_class_name( $tl_social_bucket_array[ $type ], $tl_sb_query_var );
	$detail_content['html']=$add_new_obj->viewContent();
	$detail_content['json']=stripslashes_deep($tl_social_bucket_array);
	//pr($type, true);
	echo json_encode($detail_content);
	wp_die();
}
//add new social group
add_action('wp_ajax_tl_sb_ajax_addNew', 'tl_sb_ajax_addNew');
function tl_sb_ajax_addNew(){
	$detail_content=[];
	$type=sanitize_text_field($_POST['type']);
	$type=!empty($type)?$type:'follow';
	$dyn_class_name='TLSB_'.strtoupper($type[0]).substr($type, 1, strlen($type));
	$tl_social_bucket_array = get_option( 'tl_sb_settings', [] );
	$tl_sb_query_var=[];
	$tl_sb_query_var['action'] ='addNew';	
	$tl_sb_query_var['type'] =$type;
	$add_new_obj=new $dyn_class_name($tl_social_bucket_array[$type], $tl_sb_query_var);
	$detail_content['html']=$add_new_obj->tabContent();
	$detail_content['id']=$add_new_obj->tlsb_unique_id;
	$detail_content['json']=stripslashes_deep($tl_social_bucket_array);
	echo json_encode($detail_content);
	wp_die();
}
//Edit sb group
add_action('wp_ajax_tl_sb_ajax_cb_edit', 'tl_sb_ajax_cb_edit');
function tl_sb_ajax_cb_edit(){
	$myvar			=	sanitize_text_field($_POST['social_id']);
	$type				=	sanitize_text_field($_POST['socialType']);
	$dyn_class_name='TLSB_'.strtoupper($type[0]).substr($type, 1, strlen($type));
	$tl_social_bucket_array = get_option( 'tl_sb_settings', [] );
	$tl_sb_query_var=[];
	$tl_sb_query_var['action'] ='edit';
	$tl_sb_query_var['id'] =$myvar;
	$tl_sb_query_var['type'] =$type;
	$add_new_obj=new $dyn_class_name($tl_social_bucket_array[$type], $tl_sb_query_var);
	$detail_content =$add_new_obj->tabContent();
	$active_icons =$add_new_obj->active_list_icons;
	$args=$tl_social_bucket_array[$type][$myvar][ "icons" ][$active_icons[0]];
	$args['name']=$active_icons[0];
	$args['settings']=$tl_social_bucket_array[$type][$myvar][ "settings" ];
	$detail_cont['html'] =	$detail_content;
	$detail_cont['active_icon_settings'] =	$args;
	$detail_cont['json'] =	stripslashes_deep($tl_social_bucket_array);
	
	echo json_encode($detail_cont);
	wp_die();
}