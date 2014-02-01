<?php
/*
class=HubMasterPlugin
name=Hub
author=PEMapModder
version=alpha 0.0
apiversion=11,12
*/
/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

class HubMasterPlugin implements Plugin{
	private static $inst;
	private $com;
	private static function setInstance(Plugin $i){
		self::$inst=$i;
	}
	public static function get(){
		return self::$inst;
	}
	public function __construct(ServerAPI $a, $s=0){}
	public function __destruct(){
		
	}
	public function init(){
		$this->com=new HubMgCon();
		self::setInstance($this);
		ServerAPI::request ()->addHandler("player.spawn", array($this, "initPlayer"), 3);
		ServerAPI:: request ()->addHandler("player.quit", array($this, "finalizePlayer"), 7);
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
}
