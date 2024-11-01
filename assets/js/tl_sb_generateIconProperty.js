/* icon's extra property */
var tl_sb_generateIconProperty=(function($){
	
	return {
		tl_generateStar:function(value, icon_size){
			let star='', appendarea;
			appendarea=$('.atl-sb-preview-area').find('.tl-sb-icon-head').find('.starwrap');
			if(value !='No Star'){
				switch(value){					
					case '5':
					star +='<span class="tl-star"><img src="'+tl_sb_php_data.tlsb_template_url+'assets/images/star.png" style="height:'+icon_size/6+'px;"></span>';
					star +='<span class="tl-star"><img src="'+tl_sb_php_data.tlsb_template_url+'assets/images/star.png" style="height:'+icon_size/6+'px;"></span>';
					star +='<span class="tl-star"><img src="'+tl_sb_php_data.tlsb_template_url+'assets/images/star.png" style="height:'+icon_size/6+'px;"></span>';
					star +='<span class="tl-star"><img src="'+tl_sb_php_data.tlsb_template_url+'assets/images/star.png" style="height:'+icon_size/6+'px;"></span>';
					star +='<span class="tl-star"><img src="'+tl_sb_php_data.tlsb_template_url+'assets/images/star.png" style="height:'+icon_size/6+'px;"></span>';
					break;
					default:
					star ='';
				}
			}
			appendarea.html(star);
		},
		openIconName:function(args, id){
			let html='', arrkey, settingsarea;
			settingsarea=$('.tl-sb-setting-area').find('.tl-sb-icon-name-list');
			arrkey=Object.keys(args['icons']);
			for(var icons in args['icons']){
				if(arrkey[0]==icons){
					html +='<li class="tl-sb-icon-head active pg-icon-'+icons+'" id="tl_sb_fe_'+icons+'" data-name="'+icons+'" data-save="follow>'+id+'>icons>'+icons+'>content">'+tl_cb_iconAction.iconDisplayName(icons)+'</li>';
				}else{
					html +='<li class="tl-sb-icon-head pg-icon-'+icons+'" id="tl_sb_fe_'+icons+'" data-name="'+icons+'" data-save="follow>'+id+'>icons>'+icons+'>content">'+tl_cb_iconAction.iconDisplayName(icons)+'</li>';
				}
			}
			$(html).appendTo(settingsarea);
		},
		sortingOrder:function(args){
			let prevarea=[];
			parentObject.find('.atl-sb-preview-area').find('.tl-sb-icon-head').each(function(){
				prevarea.push($(this).data('name'));
			});
			tl_cb_iconAction.tl_sb_tempSave(args, prevarea);
		},
		onHoverColor:function(iconname, args){
			let icon_bgColor;
			 switch(iconname){
				case 'facebook':
				icon_bgColor=args?args:'#5d81cd';
				break;
				case 'twitter':
				icon_bgColor=args?args:'#74bef9';
				break;
				case 'linkedin':
				icon_bgColor=args?args:'#1c99da';
				break;
				case 'pinterest':
				icon_bgColor=args?args:'#d3152a';
				break;
				case 'instagram':
				icon_bgColor=args?args:'#f2607b';
				break;
				case 'youtube':
				icon_bgColor=args?args:'#ea3635';
				break;
				case 'googlemybusiness':
				icon_bgColor=args?args:'#d7d8da';
				break;
				case 'yelp':
				icon_bgColor=args?args:'#d71919';
				break;
				case 'tumblr':
				icon_bgColor=args?args:'#6c7b91';
				break;
				case 'buffer':
				icon_bgColor=args?args:'#40a2ed';
				break;
				case 'whatsapp':
				icon_bgColor=args?args:'#28d85f';
				break;
				case 'reddit':
				icon_bgColor=args?args:'#ff7900';
				break;
				case 'fouresquare':
				icon_bgColor=args?args:'#FF77A2';
				break;
				case 'healthgrades':
				icon_bgColor=args?args:'#4343F6';
				break;
				case 'yellowpages':
				icon_bgColor=args?args:'#FCF29E';
				break;
				case 'angielist':
				icon_bgColor=args?args:'#ACEB74';
				break;
				case 'houzz':
				icon_bgColor=args?args:'#4CAF50';
				break;
			}
			return icon_bgColor;
		},
		onHoverIconColor:function(iconname, args){
			let icon_bgColor;
			 switch(iconname){
				case 'facebook':
				icon_bgColor=args?args:'#fff';
				break;
				case 'twitter':
				icon_bgColor=args?args:'#fff';
				break;
				case 'linkedin':
				icon_bgColor=args?args:'#fff';
				break;
				case 'pinterest':
				icon_bgColor=args?args:'#fff';
				break;
				case 'instagram':
				icon_bgColor=args?args:'#fff';
				break;
				case 'youtube':
				icon_bgColor=args?args:'#fff';
				break;
				case 'googlemybusiness':
				icon_bgColor=args?args:'#fff';
				break;
				case 'yelp':
				icon_bgColor=args?args:'#fff';
				break;
				case 'tumblr':
				icon_bgColor=args?args:'#fff';
				break;
				case 'buffer':
				icon_bgColor=args?args:'#fff';
				break;
				case 'whatsapp':
				icon_bgColor=args?args:'#fff';
				break;
				case 'reddit':
				icon_bgColor=args?args:'#fff';
				break;
				case 'fouresquare':
				icon_bgColor=args?args:'#fff';
				break;
				case 'healthgrades':
				icon_bgColor=args?args:'#fff';
				break;
				case 'yellowpages':
				icon_bgColor=args?args:'#000';
				break;
				case 'angielist':
				icon_bgColor=args?args:'#fff';
				break;
				case 'houzz':
				icon_bgColor=args?args:'#F3F7FF';
				break;
			}
			return icon_bgColor;
		},	
		sharePlacement:function(){
			let single_post_val, single_post_path, styckyPosVal, page_post_value, blog_opt=[];
			single_post_val				=	parentObject.find('.tl-sb-single-post').val();
			single_post_path			=	parentObject.find('.tl-sb-single-post').closest('.tl-sb-icon-placement').data('save');
			if(single_post_val){
				tl_cb_iconAction.tl_sb_tempSave(single_post_path, single_post_val);
			}
			page_post_value				=	parentObject.find('.tl-sb-page').val();
			if(page_post_value && page_post_value.length>0){
				tl_cb_iconAction.tl_sb_tempSave(parentObject.find('.tl-sb-page').closest('.tl-sb-icon-placement').data('save'), parentObject.find('.tl-sb-page').val());
			}
			
			parentObject.find('.tlsb-blogpage').find('input').each(function(){
				if($(this).is(':checked')){
					blog_opt.push($(this).val());
				}
			});
			if(blog_opt && blog_opt.length>0){
				tl_cb_iconAction.tl_sb_tempSave( parentObject.find('.tlsb-blogpage').data('save'), blog_opt );
			}
			parentObject.find('.tlsb-icon-placement').each(function(){
				styckyPosVal	=	$(this).find('.tlsb-share-sticky').find('input:checked').val();
				tl_cb_iconAction.tl_sb_tempSave($(this).find('.tlsb-share-sticky').data('save'), styckyPosVal);
				let standardArray=[];
				$(this).find('.tlsb-share-standard').find('input').each(function(){
					if($(this).is(':checked')){
						standardArray.push($(this).val());
					}
				});
				tl_cb_iconAction.tl_sb_tempSave($(this).find('.tlsb-share-standard').data('save'), standardArray);			
			});
		},
	};
})(jQuery);