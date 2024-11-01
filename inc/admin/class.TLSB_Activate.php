<?php

class TLSB_Activate{
	public static function activate(){
        //flush_rewrite_rules();
		self::insertOpt();
    }
	public static function insertOpt() {		
		$myvar = [ "follow" => [" "], "share" => [" "], "review" => [" "] ,"comment" => [" "] ];
		$prev_options = get_option("tl_sb_settings");
		if( empty( $prev_options ) ) {
			update_option('tl_sb_settings', $myvar);
		}else{
			$prev_key	=	array_keys( $prev_options );
			$current_key	=	array_keys( $myvar );
			for($i=0; $i<count($current_key); $i++){
				if(!in_array($current_key[$i], $prev_key)){
					$prev_options[$current_key[$i]]=[" "];
				}
			}
			update_option('tl_sb_settings', $prev_options);
		}
	}
}