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
}
class HubMgCom{
	private static $mgItfs=array(), $dftEvts=array(//list
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
	public function __construct(){
	 	$s=ServerAPI::request();
	 	$s->addHandler("hub.minigame.register", array($this, "registerMinigame"));
	 	foreach(self::$dftEvts as $evt)
	 	 	$s->addHandler($evt, array($this, "pmEvt"));
	}
	public function pmEvt(&$data, $event]{
		ModPE::$prvtDft=false;
		$s=ServerAPI::request();
		switch($event){
			//player-related
			case "player.block.touch":
			if($data["type"]==="break"){
				$this->plyrEctToMg($data["target"]->level, "destroyBlock", $data["player"], array($data["item"], $data["target"]));
				//onDestroyBlock
			}else{
				$this->plyrEvtToMg($data["target"]->level, "useItem", $data["player"], array($data["item"], $data["target"]));
				//onUseItem
			}
			break;case "player.interact":
				$this->plyrEvtToMg($data["player"]->entity->level, "attackHook", $data["player"], array($data["entity"]));
				//onAttack
			break;case "player.chat":
				//onChat
			break;case "player.quit":
				//onForceLeaveTournament
			break;case "player.death":
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
	public function registerCmd($cmd, $desc, $isWl){
		
	}
	public function registerMinigame(HubInterface $data){
	 	self::$mgItfs=$data;
	}
}
