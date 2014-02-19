<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class AuthApi{
	private $server;
	public function init(){
		$this->server = ServerAPI::request();
		$this->server->addHandler("player.spawn", array($this, "onSpawn"), 50);
	}
	public function onSpawn(&$p){
		$p->sendChat("Welcome to LegionPE!");
		$old = file_exists(hub_get_player_dir($p));//TODO wrong
		$pp = Hub::request()->getPlayerProfile($p);
		if($old)
			$h = $pp->get("login");
			$p->blocked = TRUE;
		}
	}
}
