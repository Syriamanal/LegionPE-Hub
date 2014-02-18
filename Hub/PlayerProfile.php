<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class PlayerProfile extends Config{
	public function __construct($ign, $filename = "main"){
		$ign = trim_player_ign($ign);
		parent::__construct(hub_get_player_dir($ign)."$filename.yml", CONFIG_YAML, array(
			"statistics" => array(
				
			),
			"personal points" => 0,
			"team index" => -1
		));
	}
}
function hub_get_player_dir($ign){
	$ign = trim_player_ign($ign);
	return FILE_PATH."hub/players/".substr($ign, 0, 1)."/$ign/";
}
