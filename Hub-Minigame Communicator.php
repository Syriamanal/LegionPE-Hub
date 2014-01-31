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
	private static $mgItfs=array(), $dftEvts=array(
		"player.block.touch",
		"player.chat",
		"player.quit",
		"item.drop",
		"player.interact",
		"player.move",
		"tile.update",
		"console.command",
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
	public function registerMinigame(HubInterface $data){
	 	self::$mgItfs=$data;
	}
}
