/*Ajax save form*/
/*
@package themelines-multi-form

*/
var tl_multi_form_ajaxCall=(function($){
	return{
		tl_form_save:function(url){
			$.ajax({
				url:url,
				success:this.tl_multi_form_ajax_success,
				error:this.tl_multi_form_ajax_error,
				complete:this.tl_multi_form_ajax_complete,
			});
		},
		tl_multi_form_ajax_success:function(data, status, xhr){
		
		},
		tl_multi_form_ajax_complete:function(xhr, statusTxt){
					
		},
		tl_multi_form_ajax_error:function(){
		}
	};
}(jQuery));