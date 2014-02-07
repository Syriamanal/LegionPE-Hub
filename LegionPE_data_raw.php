<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

class HubData{
	public static $pvpEnterSign=new Position(
				158, 31, 129, ServerAPI::request()->api->level->get("world"));
	public static $spleefSignsCoordsStart=new Position(
				100, 33, 133, ServerAPI::request()->api->level->get("world"));
	public static $spleefSignsCoordsEnd=new Position(
				100, 33, 125, ServerAPI::request()->api->level->get("world"));
	public static $cnrEnter=new Position(
				129, 31, 100, ServerAPI::request()->api->level->get("world"));
	public static $spawnCoords=new Position(
				129, 32, 129, ServerAPI:: request ()->api->level->get ("world"));
	public static $registerSpawnPt=new Position(
				167, 47, 60, ServerAPI::request()->api->level->get("world"));
	public static $chooseTeamMagma=new Position(
				166, 48, 69, ServerAPI::request()->api->level->get("world"));
	public static $chooseTeamLime=new Position(
		 	 	163, 48, 69, ServerAPI::request()->api->level->get("world"));
}
class LegionPEData{
	public function getSigns($name, $count){
		// TODO
	}
}
