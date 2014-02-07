<?php

/*
__PocketMine Plugin__
class=HubMasterPlugin
name=Hub__SimpleNameButLongPlugin
author=PEMapModder
version=alpha 0.0
apiversion=11,12
*/

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

define("HUB_SIGN_UPDATE_INTERVAL", 5*20);
class HubMasterPlugin implements Plugin{
	private static $inst;
	private $preInit=true;
	private $signs=array();
	// each => array(Tile, HubInterface)
	private $com;
	private static function setInstance(Plugin $i){
		self::$inst=$i;
	}
	public static function get(){
		return self::$inst;
	}
	public function __construct(ServerAPI $a, $s=0){
		ServerAPI::request()->event("server.start", array($this,"setupSigns"));
	}
	public function __destruct(){
		
	}
	public function init(){
		self::setInstance($this);
		$this->com=new HubMgCom();//now, let's worry about whether this is set correctly.
		$this->setupSigns();
		ServerAPI::request()->api->loadAPI("ranks", "HubRanksApi", FILE_PATH."plugins/");
		ServerAPI::request()->api->loadAPI("cmd", "HubCmdApi", FILE_PATH."plugins/");
		ServerAPI::request()->addHandler("player.spawn", array($this, "initPlayer"), 3);
		ServerAPI::request()->addHandler("player.quit", array($this, "finalizePlayer"), 7);
	}
	public function initPlayer(Player $p){
		$this->playerProfiles["$p"]=new PlayerProfile($p);
	}
	public function finalizePlayer(Player $p){
		unset($this->playerProfiles["$p"]);
	}
	public function getProfile($ign){
		return (isset($this->playerProfiles["$p"])?$this->playerProfiles["$p"]:false);
	}
	public function getPlayerMinigame($p){
		if(!($p instanceof Player))$p=ServerAPI::request()->api->player->get("$p");
		if(isset($p->entity) and isset($p->entity->level)){
			return $this->com->getMinigameByLevel($p->entity->level);
		}
		return false;
	}
	public function setupSigns(){
		$tileApi=ServerAPI::request()->api->tile;
		foreach($this->com->getAllMinigames() as $mg){
			$count=count($mg->getWorldNames());
			$signs=LegionPEData::getSigns($mg->getName(), $count);
			$tiles=array();
			foreach($signs as $sign)
				$tiles[]=$tileApi->get($sign);
			foreach($tiles as $key=>$tile){
				$tile->setText("[".$mg->getJoinStatus()[$key]."]", count($mg->getPlayerList()[$key])."/".$mg->getMaxPlayers(), ($mg->isJoinable() and $mg->getMaxPlayers()<=count($mg->getPlayerList()[$key]))?"TAP ME TO JOIN!":"CAN'T JOIN : (");
				$this->signs[$mg->getName()][$key]=$tile;
			}
		}
	}
	public function updateSigns(){
		$this->setupSigns();
		ServerAPI::request()->schedule(HUB_SIGN_UPDATE_INTERVAL, array($this, "updateSigns"));
	}
	public function onTapSign(Player $player, Position $pos){
		if($pos->level->getName()==="world"){
			$pvp=HubData::$pvpEnterSign;
			if($pos->x===$pvp->x and $pos->y===$pvp->y and $pos->z===$pvp->z){
				$mg=$this->com->get("pvp");
				
			}
		}
	}
}
