<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

function evtPrvtDft(){
	ModPE::$prvtDft=true;
}
class ModPE{
	public static $prvtDft=false; // thanks to 500ISE for this method
	public static $equipments=array();
	public static function getCarriedItem(Player $player){//I want to ensure that the player is online
		return $equipments[$player];
	}
}
class HubMgCom{
	private static $mgItfs=array(), $dftEvts=array();
	public function __construct(){
		self::$dftEvts=array(//list
			"player.block.touch",
			"player.chat",
			"player.quit",
			"item.drop",
			"player.interact",
			"player.move",
			"tile.update",
			"player.equipment.change",
			"entity.explosion",
			"player.death",
			"entity.health.change"
		);
	 	$s=ServerAPI::request();
	 	$s->addHandler("hub.minigame.register", array($this, "registerMinigame"));
	 	foreach(self::$dftEvts as $evt)
	 	 	$s->addHandler($evt, array($this, "pmEvt"));
	}
	public function pmEvt(&$data, $event){
		ModPE::$prvtDft=false;
		$s=ServerAPI::request();
		switch($event){
			case "player.equipment.change":
				ModPE::$equipments[$data["player"]->username]=$data["item"];
			//player-related
			break;case "player.block.touch":
			if($data["type"]==="break"){
				$this->plyrEvtToMg($data["target"]->level, "destroyBlock", $data["player"], array($data["item"], $data["target"]));
				//onDestroyBlock
			}else{
				$this->plyrEvtToMg($data["target"]->level, "useItem", $data["player"], array($data["item"], $data["target"]));
				//onUseItem
			}
			break;case "player.interact":
				$this->plyrEvtToMg($data["player"]->entity->level, "attack", $data["player"], array($data["entity"]));
				if($s->api->player->getByEID($data["entity"]->getEID()) instanceof Player){
					$this->plyrEvtToMg($data["player"]->entity->level, "attacked", $s->api->player->getByEID($data["entity"]->eid), array($data["player"]));
				}
				//onAttack
			break;case "player.chat":
				$this->plyrEvtToMg($data["player"]->entity->level, "chat", $data["player"], array($data["message"]));
				//onChat
			break;case "player.quit":
				$this->plyrEvtToMg($data->entity->level, "forceQuit", $data);
				//onForceQuit
			break;case "player.death":
				$this->plyrEvtToMg($data["player"]->entity->level, "die", $data["player"], array($data["cause"]));
				$killer=$s->api->player->getByEID($data["cause"]);
				if($killer instanceof Player)
					$this->plyrEvtToMg($killer->entity->level, "kill", $killer, array($data["player"]));
				//onItemDropped
			break;case "player.move":
				//onMove
			break;case "tile.update":
				//onPostPlaceSign
			//entity-related
			case "entity.health.change":
				//onHurt
			break;case "entity.explosion":
				//onExplode
			break;case "item.drop":
				//onItemDropped
			break;
		}
		return !ModPE::$prvtDft;
	}
	public function registerCmd($cmd, $desc, $power, $callback){
		return ServerAPI::request()->api->cmd->registerCmd($cmd, $desc, $power, $callback);
	}
	public function registerMinigame(HubInterface $data){
	 	self::$mgItfs[]=$data;
	}
	public function getAllMinigames(){
		return self::$mgItfs;
	}
	public function getMinigameByLevel($l){
		if(!($l instanceof Level))
			$l=ServerAPI::request()->api->level->get("$l");
		$name=$l->getName();
		foreach($this->getAllMinigames() as $mg){
			foreach($mg->getWorldNames as $wn){
				if(strtolower($wn)==strtolower($name))
					return $mg;
			}
		}
		return false;
	}
	public function getMinigameByName($name){
		foreach($this->getAllMinigames() as $mg){
			if(strtolower($mg->getName()) === strtolower($name))
				return $mg;
		}
		return false;
	}
	function plyrEvtToMg(Level $level, $evt, Player $player, $data=array()){
		$mg=$this->getMinigameByLevel($level);
		if($mg instanceof HubInterface){
			$mg->pmPlyrEvt($evt, $player, $data);
		}
		elseif($level->getName() === "world"){
			if($evt==="useItem" and ($data[1] instanceof SignPostBlock)){
				$t=ServerAPI::request()->api->tile->get($data[1])->data;
				if(substr($t["Text1"], 0, 1).substr($t["Text1"], -1) === "[]"){
					HubMasterPlugin::get()->onTapSign($player, $data[1]);
				}
			}
		}
	}
}
