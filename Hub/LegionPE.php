<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class HubPos{
	public static function getNewSpawn(){
		return new Position($x, $y, $z, ServerAPI::request()->api->level->get($level_name));//TODO lambo
	}
	public static function getOldPlayerSpawn(){
		return new Position($x, $y, $z, ServerAPI::request()->api->level->get($level_name));//TODO lambo
	}
}
