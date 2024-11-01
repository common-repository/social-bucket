<?php
if(!defined('ABSPATH')){
    exit;
   }
interface ServiceType{
	public function tabList();
	public function tabContent();
	public function tableView();
	public function script();
}
interface TLSB_AddControl{
//	public function textArea($args);
	public function textField($args);
	public function conditaionalOption($args);
	public function singleSelect($args);
	public function comboSlider($args);
	public function numberField($args);
	public function checkbox($args);
	public function radiobutton($args);
	public function groupOption($args);
	public function rightBlockOption($args);
	public function multiSelect($args);
	public function singleElem($args);
}
abstract class Controller implements TLSB_AddControl{
	//abstract function modifyHtml();
	//abstract function modifytext();
	protected $icons=[];
	protected $active_icons=[];
	public function loadIconSettings($args=[],$id='', $type='review'){
		$on_edit_script = '<script>'.
			'window.onload = function(){'.
			'let args = {};'.
			'let body_data = jQuery(".tl-sb-icon-data").text();'.
			'console.log(body_data);'.
			'body_data = JSON.parse(body_data);'.
			'args = (body_data[ "'.$type.'" ][ '.$id.' ][ "icons" ][ "'.$args[ 0 ].'" ]);'.
			'args.name = "'.$args[ 0 ].'" ;'.
			'args.settings = body_data[ "'.$type.'" ][ '.$id.' ][ "settings" ];'.
			'tl_cb_iconAction.openIconSetting(args);'.
			'tl_sb_generateIconProperty.openIconName(body_data[ "'.$type.'" ][ '.$id.' ], '.$id.');'.
			'}</script>';
		echo $on_edit_script;
	}
	public function settingsArea($is_visible=false){
		ob_start();
		if($is_visible):
		?>
		<div class = "tl-sb-setting-area">
			<div class = "tl-settings-left">
				<ul class = "tl-sb-icon-name-list"></ul>
			</div>
			<div class = "tl-settings-right"></div>				
		</div>
		<?php
		else:
		?>
		<div class = "tl-sb-setting-area" style="display:none;">
			<div class = "tl-settings-left">
				<ul class = "tl-sb-icon-name-list"></ul>
			</div>
			<div class = "tl-settings-right"></div>				
		</div>
		<?php
		endif;
		return ob_get_clean();
	}
	public function iconButton($icons=[]){
		ob_start();
		?>
		<ul class="tl-sb-buttons">
		<?php
		if(!empty($icons)){
			for($i=0; $i<count($icons); $i++){
				echo '<li class="tl-sb-botton-icon btn-icon pg-icon-'.$icons[$i].' '.(in_array($icons[$i], $this->active_icons)?'active':'no-icon').'" data-name="'.$icons[$i].'"><span class="tl-sbbutton">'.$icons[$i].'</span></li>';
			}
		}
		?>
		</ul>
		<?php
		return ob_get_clean();
	}
	public function addField($args=[]){
		$type=!empty($args)?$args['type']:'';
		if(!empty($type)){
			switch($type){
				case 'text':
				echo $this->textField($args);
				break;
				case 'textarea':
				echo $this->textArea($args);
				break;
				case 'comboslider':
				echo $this->comboSlider($args);
				break;
				case 'number':
				echo $this->numberField($args);
				break;
				case 'option':
				echo $this->singleSelect($args);
				break;
				case 'conditaionaloption':
				echo $this->conditaionalOption($args);
				break;
				case 'group-option':
				echo $this->groupOption($args);
				break;
				case 'radiobutton':
				echo $this->radiobutton($args);
				break;
				case 'checkbox':
				echo $this->checkbox($args);
				break;
				case 'right-block-option':
				echo $this->rightBlockOption($args);
				break;
				case 'multiple-select':
				echo $this->multiSelect($args);
				break;
				case 'single-element':
				echo $this->singleElem($args);
				break;
				default:
				echo '';
				break;
			}
		}
	}
	public function textField($args){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'text-field-class';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?> :</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value">
					<div class = "sb-block-value-block">
						<input class = "<?php echo $classes; ?>" name = "<?php echo $name; ?>"<?php echo !empty($id)?' id="'.$id.'"':''; ?> type = "text" value = "<?php echo $value; ?>"<?php echo !empty($path)?' data-save="'.$path.'"':'';?>/>
						<?php echo !empty($noticetxt)?$noticetxt:''; ?>
					</div>
				</div>							
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	public function comboSlider($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'text-field-class';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$min=!empty($args)&& !empty($args['min'])? $args['min']:'0';
		$max=!empty($args)&& !empty($args['max'])? $args['max']:'100';
		$step=!empty($args)&& !empty($args['step'])? $args['step']:'1';
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';		
		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?> :</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value">
					<div class = "sb-block-value-block">
						<div class = "uislider-wrapper">
							<div class="tl-sb-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-min = "<?php echo $min; ?>" data-max = "<?php echo $max; ?>" data-step = "<?php echo $step; ?>" data-value = "<?php echo $value; ?>">
								<span class = "ui-slider-handle ui-state-default ui-corner-all" tabindex = "0" style = "left: 0% ;"></span>
							</div>
							<input <?php echo !empty($id)?' id="'.$id.'"':''; ?> name="<?php echo $name; ?>" class="<?php echo $classes; ?> range-value tl-sb-value tl-ice" type = "number" value = "<?php echo $value; ?>" min = "<?php echo $min; ?>" max = "<?php echo $max; ?>" step = "<?php echo $step; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?>>
							<?php echo !empty($noticetxt)?$noticetxt:''; ?>
						</div>
					</div>
				</div>							
			</div>
		</div>
	<?php
	return ob_get_clean();
	}
	public function numberField($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$min=!empty($args)&& !empty($args['min'])? $args['min']:'0';
		$max=!empty($args)&& !empty($args['max'])? $args['max']:'1000';
		$step=!empty($args)&& !empty($args['step'])? $args['step']:'1';
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?> :</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value">
					<div class = "sb-block-value-block">
						<input <?php echo !empty($id)?' id="'.$id.'"':''; ?> type = "number" class = "<?php echo $classes; ?>" name = "<?php echo $name; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?> min = "<?php echo $min; ?>" max = "<?php echo $max; ?>" step = "<?php echo $step; ?>" value="<?php echo $value; ?>">
						<?php echo !empty($noticetxt)?$noticetxt:''; ?>
					</div>
				</div>							
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	public function singleSelect($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$options=!empty($args)&& !empty($args['options'])? $args['options']:[];
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?> :</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value select">
					<select <?php echo !empty($id)?' id="'.$id.'"':''; ?> class = "<?php echo $classes; ?>" name = "<?php echo $name; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?>>
					<?php
					if(!empty($options)){						
						foreach($options as $key=>$val){
							if(is_array($val)){	
								if(!empty($val[1]) && !empty($val[1])){
									echo '<option class="'.$val[1].'" value = "'.$key.'"'.($val===$key?' selected':'').'>'.$val[0].'</option>';
								}else{
									echo '<option value = "'.$key.'"'.($value===$key?' selected':'').'>'.$val[0].'</option>';
								}
							}else{
								echo '<option value = "'.$key.'"'.($value===$key?' selected':'').'>'.$val.'</option>';
							}
						}
					}
					?>
					</select>
					<?php echo !empty($noticetxt)?$noticetxt:''; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	public function conditaionalOption($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$options=!empty($args)&& !empty($args['options'])? $args['options']:[];
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		$c_path=!empty($args)&& !empty($args['conditionpath'])? $args['conditionpath']:'';
		$c_class=!empty($args)&& !empty($args['conditionclass'])? $args['conditionclass']:'';
		$c_name=!empty($args)&& !empty($args['conditionname'])? $args['conditionname']:'';
		$c_value=!empty($args)&& !empty($args['conditionvalue'])? $args['conditionvalue']:'';
		$c_opt=!empty($args)&& !empty($args['conditionoption'])? $args['conditionoption']:'';
		$c_wrap_class=!empty($args)&& !empty($args['conditionwrapclass'])? $args['conditionwrapclass']:'';

		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?>:</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value">
					<div class = "sb-block-value-block">
						<div class = "tl-sb-stickyopt">
							<div class = "ch-bx">
								<input type="checkbox" <?php echo !empty($id)?' id="'.$id.'"':''; ?> class = "<?php echo $classes; ?>" style = "display:none" value="<?php echo $value; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?>/>
								<label for = "<?php echo !empty($id)?$id:''; ?>" class = "toggle"><span></span>
							</div>
							<?php if(!empty($c_opt)):?>
							<div class="tl-sb-stickyenable select"<?= empty($value)?' style="display:none;"':'' ?>>
								<select class = "<?php echo $c_class; ?>" name = "<?php echo $c_name; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?>>
								<?php
									if(!empty($c_opt)){
										for($i=0; $i<count($c_opt); $i++){
											echo '<option value = "'.$c_opt[$i].'"'.($c_value===$c_opt[$i]?' selected':'').'>'.$c_opt[$i].'</option>';
										}
									}
									?>
								</select>									 
							</div>	
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>	
	<?php
	return ob_get_clean();
	}
	public function groupOption($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'Title';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$groupLabel=!empty($args)&& !empty($args['groupLabel'])? $args['groupLabel']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		$wraper_class=!empty($args)&& !empty($args['wraper_class'])? $args['wraper_class']:'';
		$subhtml=!empty($args)&& !empty($args['subset'])? $args['subset']:[];
		ob_start();
		?>
		<div class = "sb-row-block">
			<div class = "tl-sb-col sb-block-left"><label><?php echo $title; ?> :</label></div>
			<div class = "tl-sb-col sb-block-right">
				<div class = "sb-block-value">
					<?php if(!empty($groupLabel) && !empty($groupLabel)):?>
						<div class = "sb-block-value-label"><span><?=$groupLabel?></span></div>
					<?php endif;
					if(!empty($wraper_class) || !empty($path)){
					?>
						<div class="<?php echo $wraper_class;?>">
					<?php
					}
					if(!empty($id) || !empty($classes) || !empty($path)){
					?>
					<div <?php echo !empty($id)?' id="'.$id.'"':'';?> class="<?php echo $classes; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?>>
						<?php 
					}
						if(!empty($subhtml)):
							for($i=0;$i<count($subhtml);$i++){
								$this->addField($subhtml[$i]);
							}
						endif;
						?>
						<?=$noticetxt?>	
					<?php if(!empty($id) || !empty($classes) || !empty($path)){ ?>
					</div>
					<?php }
					if(!empty($wraper_class) || !empty($path)){		?>
						</div>
					<?php					}						?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	public function radiobutton($args=[]){
		$value=!empty($args) && !empty($args['value'])?$args['value']:'';
		$id=!empty($args) && !empty($args['id'])?$args['id']:'';
		$name=!empty($args) && !empty($args['name'])?$args['name']:'';
		$classes=!empty($args) && !empty($args['class'])?$args['class']:'';
		$wraper_class=!empty($args) && !empty($args['wraper_class'])?$args['wraper_class']:'';
		$content_text=!empty($args) && !empty($args['content_text'])?$args['content_text']:'';
		$checkvalue=!empty($args) && !empty($args['checkvalue'])?$args['checkvalue']:[];
		$opt_cls=!empty($args) && !empty($args['opt_cls'])?' '.$args['opt_cls']:'';
	/* echo '<pre>';
	print_r($checkvalue); */
		ob_start();
		if(!empty($wraper_class)){
		?>
			<div class="<?php echo $wraper_class;?>">
		<?php
		}
		?>			
			<span class="opt-cls<?=$opt_cls?>"><input type="radio" name="<?php echo $name; ?>" <?php echo !empty($id)?' id="'.$id.'"':''; ?> class = "<?php echo $classes; ?>" <?php echo in_array( $value,$checkvalue )?' checked':''; ?> <?php echo (!empty($value)?'value="'.$value.'"':'');?> />
				<?php if(!empty( $content_text )) echo $content_text; ?>
			</span>
		<?php
		if(!empty($wraper_class)){
			?>
				</div>
			<?php
		}
		return ob_get_clean();
	}
	public function checkbox($args=[]){
		$value=!empty($args) && !empty($args['value'])?$args['value']:'';
		$id=!empty($args) && !empty($args['id'])?$args['id']:'';
		$name=!empty($args) && !empty($args['name'])?$args['name']:'';
		$classes=!empty($args) && !empty($args['class'])?$args['class']:'';
		$wraper_class=!empty($args) && !empty($args['wraper_class'])?$args['wraper_class']:'';
		$content_text=!empty($args) && !empty($args['content_text'])?$args['content_text']:'';
		$checkvalue=!empty($args) && !empty($args['checkvalue'])?$args['checkvalue']:[];		
		$opt_cls=!empty($args) && !empty($args['opt_cls'])?' '.$args['opt_cls']:'';

		ob_start();
		if(!empty($wraper_class)){
			?>
				<div class="<?php echo $wraper_class;?>">
			<?php
			}
		?>			
			<span class="opt-cls<?=$opt_cls?>"><input type="checkbox" name="<?php echo $name; ?>" <?php echo !empty($id)?' id="'.$id.'"':''; ?> class = "<?php echo $classes; ?>" <?php echo in_array($value,$checkvalue)?' checked':''; ?> <?php echo (!empty($value)?'value="'.$value.'"':'');?> />		
			<?php if(!empty($content_text)) echo $content_text; ?>
			</span>
		<?php
		if(!empty($wraper_class)){
			?>
				</div>
			<?php
		}		
		return ob_get_clean();
	}
	public function rightBlockOption($args){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$wraper_class=!empty($args) && !empty($args['wraper_class'])?$args['wraper_class']:'';
		$content_text=!empty($args) && !empty($args['content_text'])?$args['content_text']:'';
		$subhtml=!empty($args)&& !empty($args['subset'])? $args['subset']:[];
		
		ob_start();
		?>
		<?php if(!empty($wraper_class)):?>
			<div class="<?php echo $wraper_class; ?>">
			<?php endif; ?>
		
			<?php if(!empty($title)):?><div class="tlsb-icon-settings-label"><?=$title?></div><?php endif; ?>
			<?php if(!empty($classes) || !empty($path)):?>
			<div class="<?=$classes?>" data-save = "<?=$path?>">
			<?php endif; ?>
				<?php
					if(!empty($subhtml)):
						for($i=0;$i<count($subhtml);$i++){
							$this->addField($subhtml[$i]);
						}
					endif;
				?>
		<?php if(!empty($classes) || !empty($path)):?>	</div> <?php endif; ?>
		<?php if(!empty($wraper_class)):?>
			</div>
			<?php endif; ?>
	<?php
	return ob_get_clean();
	}
	public function multiSelect($args=[]){
		$title=!empty($args)&& !empty($args['title'])? $args['title']:'';
		$name=!empty($args)&& !empty($args['name'])? $args['name']:'name';
		$value=!empty($args)&& !empty($args['value'])? $args['value']:'';
		$id=!empty($args)&& !empty($args['id'])? $args['id']:'';
		$classes=!empty($args)&& !empty($args['class'])? $args['class']:'';
		$path=!empty($args)&& !empty($args['path'])? $args['path']:'';
		$options=!empty($args)&& !empty($args['options'])? $args['options']:[];
		$noticetxt=!empty($args)&& !empty($args['noticetxt'])? $args['noticetxt']:'';
		$wraper_class=!empty($args) && !empty($args['wraper_class'])?' '.$args['wraper_class']:'';	
		
		ob_start();
		?>
				<?php if(!empty($wraper_class)):?>
				<div class="<?=$wraper_class?>">
				<?php endif; ?>
				<div class = "sb-block-value">
					<select <?php echo !empty($id)?' id="'.$id.'"':''; ?> class = "<?php echo $classes; ?>" name = "<?php echo $name; ?>" <?php echo !empty($path)?' data-save="'.$path.'"':'';?> multiple>
					<?php
					if(!empty($options)){
						foreach($options as $key=>$val){
							if(is_array($val)){	
								if(!empty($val[1])){
									echo '<option class="'.$val[1].'" value = "'.$key.'"'.(in_array($key, $value)?' selected':'').'>'.$val[0].'</option>';
								}else{
									echo '<option value = "'.$key.'"'.(in_array($key, $value)?' selected':'').'>'.$val[0].'</option>';
								}
							}else{
								echo '<option value = "'.$key.'"'.(in_array($key, $value)?' selected':'').'>'.$val.'</option>';
							}
						}
					}
					?>
					</select>
					<?php echo !empty($noticetxt)?$noticetxt:''; ?>
				</div>
				<?php if(!empty($wraper_class)):?>
				</div>
				<?php endif; ?>
		<?php
		return ob_get_clean();
	}
	public function singleElem($args=[]){
		if(!empty($args)){
			$classes=!empty($args) && !empty($args['class'])?$args['class']:'';
			$wraper_class=!empty($args['wraper_class'])?$args['wraper_class']:'';
			$content_text=!empty($args['content_text'])?$args['content_text']:'';
			$subhtml=!empty($args['subset'])? $args['subset']:[];
			$path=!empty($args)&& !empty($args['path'])?' data-save="'. $args['path'].'"':'';
			
			ob_start();
			?>
			<?php if(!empty($wraper_class)):?> <div class="<?=$wraper_class?>"<?=$path?>><?php endif; ?>
				<?php if(!empty($classes)):?> <span class="<?=$classes?>"><?=$content_text?></span><?php endif; ?>
				<?php 
				if(!empty($subhtml)):
					for($i=0;$i<count($subhtml);$i++){
						$this->addField($subhtml[$i]);
					}
				endif;
				?>
			<?php if(!empty($wraper_class)):?> </div><?php endif; ?>
			<?php
			return ob_get_clean();
		}
	}
}