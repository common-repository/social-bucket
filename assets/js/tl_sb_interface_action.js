let socialId='', tl_sb_type, formattedJson={}, parentObject=null, socialType=null, previewArea='';
(function($){	
	//Onload effects
	$(document).ready(function(){
		var tl_sb_form=$(document).find('.tl-sb-form');
		socialType=$('.tl-sb-social-text.active').data('name');
		socialId=$('.tl-sb-social-text.active').data('id');			
		let deformattedJson=$('#tl-prev-obj-value').val();
		//deformattedJson='"'+deformattedJson+'"';
			if(deformattedJson.trim().length>0){
				formattedJson=JSON.parse(deformattedJson);
			}	
		$('.tl-sb-icon-data').text($('#tl-prev-obj-value').val());
		var activeFormType=tl_sb_form.find('.tl-sb-socialtype.active');
		parentObject=activeFormType;
		previewArea=parentObject.find(".atl-sb-preview-area");
		tl_sb_type='.'+parentObject.data('name');
		$('.tl-sb-table').children().removeClass('active');
		$('.tl-sb-preview-'+socialType).addClass('active');
		
		if(previewArea.find('div').hasClass('tl-sb-icon-head')){
			previewArea.find('h3').hide();
			parentObject.find('.tl-sb-setting-area').show();
		}else{
			previewArea.find('h3').show();
			parentObject.find('.tl-sb-setting-area').hide();
		}	
		
		//Tagging triology event
		$('.tl-sb-social-type').find('.tl-sb-social-text').on('click', function(){
			activeFormType=tl_sb_form.find('.tl-sb-socialtype.active');
			if($(this).hasClass('active')){											//If click on the selected tag
				return;																// return nothing and make no action
			}else{	
				$(this).siblings().add(activeFormType).removeClass('active');		//
				$(this).addClass('active');											//
				var tl_sb_template=$(this).data('type');							//
				tl_sb_form.find('.'+tl_sb_template).addClass('active');
				parentObject=tl_sb_form.find('.'+tl_sb_template);
				previewArea=parentObject.find(".atl-sb-preview-area");
				tl_sb_type='.'+tl_sb_template;
				socialType=$(this).data('name');
				$('.tl-sb-table').children().removeClass('active');
				$('.tl-sb-preview-'+socialType).addClass('active');
				/*  ---- Social Comment ---- */
				/* if(socialType != 'comment') {
					$('.tl-sb-table').children().removeClass('active');
					$('.tl-sb-preview-'+socialType).addClass('active');
				} else {
					if($('.tl-sb-comment-enable').prop("checked") == true) {
						href = 'admin.php?page=tl-social-bucket&action=edit&id=1&type=comment'
						window.location.assign(href);
					} else {						
						$('.tl-sb-table').children().removeClass('active');
						$('.tl-sb-preview-'+socialType).addClass('active');
					}
				} */
			}
		});
		
		
		$(document).on('click', '.add-new-btn-block', function(e){			
			e.preventDefault();
			socialType = $('.tl-sb-social-text.active').data('name');
			let href=$(this).find('.tl-sb-anchor-btn').attr('href');
			let data={'action': 'tl_sb_ajax_addNew', 'type':socialType};
			$.post(tl_sb_php_data.ajax_uri, data, function(response){
				var body = $("html, body");
				body.animate({scrollTop:0}, '500');
				adding();
				setTimeout(function(){adding();},1500);
				var contain=JSON.parse(response);
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').empty();
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').html(contain['html']);
				$(document).find('.tl-sb-social-text.active').attr('data-id', contain['id']);
				var activeFormType=tl_sb_form.find('.tl-sb-socialtype.active');
				parentObject=activeFormType;
				previewArea=parentObject.find(".atl-sb-preview-area");
				tl_sb_type='.'+parentObject.data('name');
				socialType=$('.tl-sb-social-text.active').data('name');
				socialId=contain['id'];					
				formattedJson=contain['json'];				
				$('.tl-sb-icon-data').text($('#tl-prev-obj-value').val());
				tl_cb_iconAction.createRangeSlider();
			});
		});	
		
		$(document).on('click', '.tl-sb-botton-icon', function(){
			let icon_props={};
			var activeFormType=tl_sb_form.find('.tl-sb-socialtype.active');
			parentObject=activeFormType;
			previewArea=parentObject.find(".atl-sb-preview-area");
			icon_props.previewArea=previewArea;
			icon_props.icon_size=parentObject.find('.tl-sb-follow-icon-size-default').val();
			icon_props.icon_bgShape=parentObject.find('.tl-sb-follow-shape').val();
			icon_props.name=$(this).data('name');
			if($(this).hasClass('no-icon')){
				tl_cb_iconAction.appendIcon(icon_props);
				$(this).removeClass('no-icon').addClass('active');				
				parentObject.find('.tl-sb-submit-alert-message').hide();
			}else{
				$(this).addClass('no-icon');
				$(this).removeClass('active');
				tl_cb_iconAction.removeIcon($(this), previewArea);
			}
			if(previewArea.find('div').hasClass('tl-sb-icon-head')){
				previewArea.find('h3').hide();
				parentObject.find('.tl-sb-setting-area').show();
			}else{
				previewArea.find('h3').show();
				parentObject.find('.tl-sb-setting-area').hide();
			}	
		});
		
		$(document).on('change', '.tl-sb-follow-icon-size-default', function(){
			let value=$(this).val(), _this=parentObject.find(".atl-sb-preview-area");
			_this.find('.tl-sb-icon').attr('height', value);
			_this.find('.tl-sb-icon').attr('width', value);
			_this.find('.tl-star').find('img').css('height', value/6+'px');
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), value);		
		});
		
		$(document).on('change', '.definedslug input', function(){
			if($(this).is(':checked')){
				tl_cb_iconAction.tl_sb_tempSave($(this).closest('.definedslug').data('save'), $(this).val());
			}
		});
		
		$(document).on('change', '.tl-sb-follow-icon-bgcolor-hover', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		$(document).on('change', '.tl-sb-follow-icon-bgcolor-default', function(){
			let value=$(this).val();
			let target=$(this).closest('.tl-sb-iconLocator').data('name');
			$(document).find(".atl-sb-preview-area").find('#'+target).find('.tl-sb-bg').css('fill', value);
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), value);
		});
		
		$(document).on('change', '.tl-sb-follow-icon-color-hover', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		$(document).on('change', '.tl-sb-follow-icon-color-default', function(){
			let value=$(this).val();
			let target=$(this).closest('.tl-sb-iconLocator').data('name');
			parentObject.find(".atl-sb-preview-area").find('#'+target).find('.tl-sb-fg').css('fill', value);
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), value);
		});
		$(document).on('change', '.tl-sb-follow-shape', function(){
			let body_data, icon, icon_bgColor, bgset, icon_size, icon_bgShape, typeOfIconBG, icon_color;
			body_data=$('.tl-sb-icon-data').text();
			body_data=JSON.parse(body_data);
			icon_bgShape=$(this).val();
			typeOfIconBG=tl_cb_iconAction.typeOfIconBG(icon_bgShape);
			icon_size=parentObject.find('.tl-sb-follow-icon-size-default').val();
			parentObject.find(".atl-sb-preview-area").find('.tl-sb-icon-head').each(function(){
				icon=$(this).data('name');
				icon_bgColor=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['bgcolor'], ['bydefault'])||'';
				icon_color=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['color'], ['bydefault'])||'#ffffff';
				icon_bgColor=tl_cb_iconAction.defaultIconBg(icon, icon_bgColor);
				bgset=window[typeOfIconBG][icon](icon_size, icon_color, icon_bgColor);
				$(this).find('.tl-sb-icon-wrapper').html(bgset);			
			});
		});
		$(document).on('click', '.tlsb-placement-key span', function(){
			if($(this).hasClass('active')) return;
			let data_type	=	$(this).data('type');
			$(this).siblings().removeClass('active');
			$(this).addClass('active');
			$(this).parent().nextAll().hide();
			$(this).closest('.tlsb-placement-wrapper').find('.'+data_type).show();
			
		});
		$(document).on('change', '.definedslug-opt :radio', function(){
			parentObject.find('#tl-sb-share-customslug').hide();
			if($(this).val()=='customslug'){
				parentObject.find('#tl-sb-share-customslug').show();
			}
		});
		$(document).on('change', '.tl-sb-follow-isSticky', function(){
			let block=$(this).closest('.sb-block-value-block').find('.tl-sb-follow-icon-stickyPos');
			block.hide();
			if($(this).is(':checked')){
				block.show();
			}
		});
		$(document).on('change', '.tl-sb-review-showReview', function(){
			
		});
		$(document).on('input', '.tl-sb-icon-url', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		/* ----- Comment Label ----- */
		$(document).on('input', '.tl-sb-icon-label', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		/* ---- Review Details Save Start ---- */
		$(document).on('input', '.tl-sb-icon-apikey', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		$(document).on('input', '.tl-sb-icon-placeid', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		$(document).on('input', '.tl-sb-icon-minrating', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		$(document).on('input', '.tl-sb-icon-pageid', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		
		$(document).on('input', '.tl-sb-icon-accessToken', function(){
			tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		});
		/* ---- Review Details Save End ---- */
		
		
		$(document).on('click', '.tl-sb-icon-head', function(e){
			e.stopPropagation();
			let icon=$(this).data('name');
			let body_data=$('.tl-sb-icon-data').text();
			 body_data=JSON.parse(body_data);
			 let args={};
			 args.name=icon;
			if(body_data[socialType] && body_data[socialType] && body_data[socialType][socialId] && body_data[socialType][socialId]['icons'][icon]){
				args=(body_data[socialType][socialId]['icons'][icon]);	
				args.name=icon;
				args.settings=(body_data[socialType][socialId]['settings']);
			}
			tl_cb_iconAction.openIconSetting(args);
		});
		
		$(document).on('click', '.tl-sb-submit-button', function(){
			let _id=$('.tl-sb-social-text').data('id');
			
			if(parentObject.find('.tl-sb-preview-area').contents().hasClass('tl-sb-icon-head')){
				parentObject.find('.tl-sb-submit-alert-message').hide();
				if(socialType == 'comment') {
					tl_cb_iconAction.tl_sb_tempSave('comment>1>activate', 'true');
				} 
				/* else if(socialType == 'review') {
					var place_id = parentObject.find('.tl-sb-icon-placeid').val();
					var map = new google.maps.Map(place_id);
					var request = {
						placeId: place_id
					};
					
					var service = new google.maps.places.PlacesService(map);

					service.getDetails(request, function(place, status) {
						if (status == google.maps.places.PlacesServiceStatus.OK) {
							var enable_review = parentObject.find('#tl-sb-review-showReview').val();
							tl_cb_iconAction.tl_sb_tempSave(socialType+ '>' + socialId + '>icons>googlemybusiness>user_rating', place.rating.toString());
							if(enable_review == 'on') {
								tl_cb_iconAction.tl_sb_tempSave(socialType+ '>' + socialId + '>icons>googlemybusiness>user_review', place.reviews);
							}
							
						}
					});
				} */
				
				tl_cb_iconAction.immediateSave();
				
				let body_data=$('.tl-sb-icon-data').text();
				json_data=JSON.parse(body_data);
				let data={'action': 'tl_sb_ajax_cb_action', 'social_data':json_data, 'type':socialType};
				$.post(tl_sb_php_data.ajax_uri, data, function(response){
					var contain=JSON.parse(response);
					formattedJson=contain['json'];
					$('.tl-social-bucket-wrapper').find('.tl-sb-form').empty();
					success();
					setTimeout(function(){success();},2000);
					tl_cb_iconAction.ajaxCallbac();
					//if(socialType != 'comment') {
						$(document).find('.tl-sb-data-row.tl-sb-preview-'+socialType).html(contain['html']);	
					/* } else {
						window.location.assign('admin.php?page=tl-social-bucket&action=edit&id=1&type=comment');
					} */
				});
			}else{
				parentObject.find('.tl-sb-submit-alert-message').show();
				return;
			}
		});
		$(document).on('click', '#tl_sb_sgroup_delete', function(e){
			e.preventDefault();
			socialType=$(document).find('.tl-sb-social-text.active').data('name');
			let _id=$(this).data('id');		
			let data={'action': 'tl_sb_ajax_cb_delete', 'social_id':parseInt(_id), 'socialType':socialType};
			$.post(tl_sb_php_data.ajax_uri, data, function(response) {
				var contain=JSON.parse(response);
				formattedJson=contain['json'];
				deleting();
				setTimeout(function(){deleting();},2000);
				//window.location.assign('admin.php?page=tl-social-bucket');
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').empty();
				$(document).find('.tl-sb-data-row.tl-sb-preview-'+socialType).html(contain['html']);
			});
		});
		$(document).on('click', '#tl_sb_sgroup_edit', function(e){
			e.preventDefault();
			
			socialType=$(document).find('.tl-sb-social-text.active').data('name');
			let _id=$(this).data('id');	
			$(document).find('.tl-sb-social-text.active').attr('data-id', _id);
			let data={'action': 'tl_sb_ajax_cb_edit', 'social_id':parseInt(_id), 'socialType':socialType};
			$.post(tl_sb_php_data.ajax_uri, data, function(response) {
				var body = $("html, body");
				body.animate({scrollTop:0}, '1000');
				loading();
				setTimeout(function(){loading();},2000);
				let return_val=JSON.parse(response);
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').empty();
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').html(return_val['html']);
				var activeFormType=tl_sb_form.find('.tl-sb-socialtype.active');
				parentObject=activeFormType;
				previewArea=parentObject.find(".atl-sb-preview-area");
				tl_sb_type='.'+parentObject.data('name');
				socialType=$('.tl-sb-social-text.active').data('name');
				socialId=_id;
				formattedJson=return_val['json'];
				$('.tl-sb-icon-data').text($('#tl-prev-obj-value').val());				
				$('.tl-sb-table').children().removeClass('active');
				$('.tl-sb-preview-'+socialType).addClass('active');
				if(previewArea.find('div').hasClass('tl-sb-icon-head')){
					previewArea.find('h3').hide();
					parentObject.find('.tl-sb-setting-area').show();
				}else{
					previewArea.find('h3').show();
					parentObject.find('.tl-sb-setting-area').hide();
				}	
				tl_cb_iconAction.openIconSetting(return_val['active_icon_settings']);
				tl_sb_generateIconProperty.openIconName(formattedJson[ socialType ][ _id ], _id);
				tl_cb_iconAction.createRangeSlider();
			});
		});
		$('.atl-sb-preview-area').sortable({
			cursor:'move',
		});
		
		$('.copy-this').off('click').on('click', function(){
			theShortcode = $(this).parents('div.tl-sb-shortcode').find('.tl-sb-the-shortcode');
			copyToClipboard(theShortcode);
			$(this).parents('div.tl-sb-shortcode').find('span.copied-to-clipboard').show();
			$(this).parents('div.tl-sb-shortcode').find('span.copied-to-clipboard').fadeOut(3000);
		});
		$(document).on('change', '.tl-sb-follow-addStar', function(){
			let value='', _this, icon_size;
			icon_size=parentObject.find('.tl-sb-follow-icon-size-default').val();
			_this=$(this);
			if(_this.is(':Checked')){
				value='5';				
			}	
			tl_sb_generateIconProperty.tl_generateStar(value, icon_size);
		});
			
		// Only for preview
		 $(document).on('mouseenter', '.atl-sb-preview-area .tl-sb-icon-head', function(){
			let icon=$(this).data('name');
			let body_data=$(document).find('.tl-sb-icon-data').text();
			 body_data=JSON.parse(body_data);
			let icon_color=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['color'], ['hover'])||'#ffffff';
			let bg_color=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['bgcolor'], ['hover'])||tl_sb_generateIconProperty.onHoverColor(icon, null);
			$(this).find('.tl-sb-bg').each(function(){
				var attr=$(this).attr("style");
				if (typeof attr !== typeof undefined && attr !== false){
					$(this).css('fill', bg_color);
				}
			});		
			$(this).find('.tl-sb-fg').each(function(){
				var attr=$(this).attr("style");
				if (typeof attr !== typeof undefined && attr !== false){
					$(this).css('fill', icon_color);
				}
			});		
		});
		$(document).on('mouseleave', '.atl-sb-preview-area .tl-sb-icon-head', function(){
			let icon=$(this).data('name');
			let body_data=$('.tl-sb-icon-data').text();
			 body_data=JSON.parse(body_data);
			let icon_color=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['color'], ['bydefault'])||tl_sb_generateIconProperty.onHoverIconColor(icon, null);
			let bg_color=checkValue(body_data, [socialType], [socialId], ['icons'], [icon], ['bgcolor'], ['bydefault'])||tl_cb_iconAction.defaultIconBg(icon, null);
			$(this).find('.tl-sb-bg').each(function(){
				var attr=$(this).attr("style");
				if (typeof attr !== typeof undefined && attr !== false) {
					$(this).css('fill', bg_color);
				}
			}); 
			$(this).find('.tl-sb-fg').each(function(){
				var attr=$(this).attr("style");
				if (typeof attr !== typeof undefined && attr !== false){
					$(this).css('fill', icon_color);
				}
			});
		}); 
		tl_cb_iconAction.createRangeSlider();
		$(document).find('.tl-sb-slider').slider();
		$(document).on('change', '.tl-sb-value', function(){
			$(this).siblings('.tl-sb-slider').slider( "option", "value", $(this).val());
		});
	});
	
	/* ----------- Enable / Disable Comment ------------- */
	$('.tl-sb-comment-enable').on('change',function() {
		tl_cb_iconAction.tl_sb_tempSave($(this).data('save'), $(this).val());
		let body_data=$('.tl-sb-icon-data').text();
				json_data=JSON.parse(body_data);
		if($(this).is(':checked')){
			let data={'action': 'tl_sb_comment_disable_action', 'social_data':json_data, 'type':socialType};
			$.post(tl_sb_php_data.ajax_uri, data, function(response){
				var return_val=JSON.parse(response);
				success();
				setTimeout(function(){success();},2000);
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').empty();
				$('.tl-social-bucket-wrapper').find('.tl-sb-form').html(return_val['body']);
				$(document).find('.tl-sb-data-row.tl-sb-preview-comment').html(return_val['table']);
				var activeFormType=$(document).find('.tl-sb-social-type').find('.tl-sb-social-text.active');
				parentObject=$('.'+activeFormType.data('type'));
				previewArea=parentObject.find(".atl-sb-preview-area");
				console.log(parentObject);
				tl_sb_type='.'+parentObject.data('name');
				socialType=$('.tl-sb-social-text.active').data('name');
				socialId=1;
				formattedJson=return_val['json'];
				$('.tl-sb-icon-data').text($('#tl-prev-obj-value').val());				
				$('.tl-sb-table').children().removeClass('active');
				$('.tl-sb-preview-comment').addClass('active');
				if(previewArea.find('div').hasClass('tl-sb-icon-head')){
					previewArea.find('h3').hide();
					parentObject.find('.tl-sb-setting-area').show();
				}else{
					previewArea.find('h3').show();
					parentObject.find('.tl-sb-setting-area').hide();
				}	
				tl_cb_iconAction.openIconSetting(return_val['active_icon_settings']);
				tl_sb_generateIconProperty.openIconName(formattedJson[ socialType ][ 1 ], 1);
				tl_cb_iconAction.createRangeSlider();
			});
		}
		/* if($('.tl-sb-comment-enable').prop("checked") == true) {
			let href = $('.add-new-btn-block').find('.tl-sb-anchor-btn').attr('href');
			href = href + '&type='+socialType;
			window.location.assign(href);
		} else {
			
		} */
	});
})(jQuery);
function copyToClipboard(element) {
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	$temp.val(jQuery(element).val()).select();
	document.execCommand('copy');
	$temp.remove();
}

function success(){
  jQuery('.message').toggleClass('comein');
  jQuery('.save-msg').toggleClass('inactive-display');
  jQuery('.check').toggleClass('scaledown');
}
function deleting(){
  jQuery('.delete-message').toggleClass('comein');
  jQuery('.delete-msg').toggleClass('inactive-display');
  jQuery('.delete-check').toggleClass('scaledown');
}
function loading(){
  jQuery('.setting-animation').toggle();
}
function adding(){
  jQuery('.tlsb-add-new-animation').toggle();
}

function checkValue(){	
var statickey;
	var argsVal=arguments[0];
	var i=1;
	do{
		argsVal=argsVal[arguments[i]];	
		if(argsVal){
			statickey=argsVal;		
			i++;
		}else{
			statickey=false;		
			i=arguments.length;
			}
	}while(i<arguments.length)
	return statickey;
}