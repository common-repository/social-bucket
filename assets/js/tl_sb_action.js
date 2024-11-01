					jQuery(document).on("mouseenter", ".tl-sb-icon-head", function(){
						let icon=jQuery(this).data("name");
						let body_data=jQuery(this).find(".tl-sb-icon-individual-data").text();
						body_data=JSON.parse(body_data);
						let icon_color=body_data["iconHover"]?body_data["iconHover"]:"";
						let bg_color=body_data["bgHover"]?body_data["bgHover"]:"";
			
					jQuery(this).find(".tl-sb-icon-individual-data").text(JSON.stringify(body_data));
						if(bg_color){
							jQuery(this).find(".tl-sb-bg").each(function(){
								var attr=jQuery(this).attr("style");
								if (typeof attr !== typeof undefined && attr !== false){
									jQuery(this).css("fill", bg_color);
								}
							});
						}
						if(icon_color){
							jQuery(this).find(".tl-sb-fg").each(function(){
								var attr=jQuery(this).attr("style");
								if (typeof attr !== typeof undefined && attr !== false){
									jQuery(this).css("fill", icon_color);
								}
							});		
						}
					});
					jQuery(document).on("mouseleave", ".tl-sb-icon-head", function(){
						let icon=jQuery(this).data("name");
						let body_data=jQuery(this).find(".tl-sb-icon-individual-data").text();
						body_data=JSON.parse(body_data);
						let icon_color=body_data["iconDefault"]?body_data["iconDefault"]:"";
						let bg_color=body_data["bgDefault"]?body_data["bgDefault"]:"";	
						jQuery(this).find(".tl-sb-bg").each(function(){
							var attr=jQuery(this).attr("style");
							if (typeof attr !== typeof undefined && attr !== false) {
								jQuery(this).css("fill", bg_color);
							}
						});
						jQuery(this).find(".tl-sb-fg").each(function(){
							var attr=jQuery(this).attr("style");
							if (typeof attr !== typeof undefined && attr !== false){
								jQuery(this).css("fill", icon_color);
							}
						});
					});