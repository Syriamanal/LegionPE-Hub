<?php

class PlayerProfile extends Config{
	public function __construct($ign, $filename = "main"){
		$ign = trim_player_ign($ign);
		parent::__construct(hub_get_player_dir($ign)."$ign.yml", CONFIG_YAML, array(
			
		));
	}
}
function hub_get_player_dir($ign){
	$ign = trim_player_ign($ign);
	return FILE_PATH."hub/players/".substr($ign, 0, 1)."/";
}
