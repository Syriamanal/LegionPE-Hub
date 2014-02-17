<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_modder_for_maps at http://minecraftforum.net
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
		switch(strtolower($name)){
		case "pvp":
			$pos=array(HubData::$pvpSignStart, HubData::$pvpSignEnd);
			break;
		case "cnr":
			$pos=array(HubData::$cnrSignStart, HubData::$cnrSignStart);
			break;
		case "spleef":
			$pos=array(HubData::$spleefSignStart, HubData::$spleefSignEnd);
			break;
		case "pk":
			$pos=array(HubData::$pkSignStart, HubData::$pkSignEnd);
		}
		if(!isset($pos))return false;
		$height=abs($pos[0]->y-$pos[1]->y+1);
		$mod=$count%$height;
		$count-=$mod;
		$dc=(($pos[0]->x==$pos[1]->x)?"z":"x");
		$requiredWidth=$count/$height;
		if($requiredWidth%2===1){//easy
			$mid=($requiredWidth-1)/2;
			$start=min($pos[0]->$dc, $pos[1]->$dc);
			$end=max($pos[0]->$dc, $pos[1]->$dc);
			$mid+=$start;
		}
		else{//hard
			
		}
//	#	#	#	#	#	#	#	#	#	#	#	#	#	#	#
//	#	#	#	#	#	#	#	x	#	#	#	#	#	#	#
//	#	#	#	#	#	#	#	#	#	#	#	#	#	#	#
	}
}
