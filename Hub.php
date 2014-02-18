<?php

/*
__PocketMine Plugin__
class=Hub
name=LegionPE Hub
version=beta 1.0
apiversion=12
author=PEMapModder
*/

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

hub_require_all();
class Hub implements Plugin{ // I really planned to use this class name, not any generic class name.
	public static function request(){
		return self::$instance;
	}
	private $api, $server;
	public $mgCom, $pmCom;
	public $playerProfiles = array();
	public function init(){
		$this->mgCom = new MinigameCom($this);
		$this->mgCom->init();
		$this->initEvents();
		$this->cmdApi = new CmdApi();;
		$this->cmdApi->init();
	}
	public function initEvents(){
		$s =& $this->server;
		$s->addHandler("player.spawn", array($this, "onSpawn"));
		$s->addHandler("player.quit", array($this, "onQuit"));
	}
	public function onSpawn(Player &$player){
		$ign = trim_player_ign($player);
		$this->playerProfiles[$ign] = new PlayerProfile($ign);
	}
	public function onQuit(Player &$player){
		if(!isset($this->playerProfiles[trim_player_ign($player)]))
			return;
		$ign = trim_player_ign($player);
		$this->playerProfiles[$ign] = FALSE;
		unset($this->playerProfiles[$ign]);
	}
	public function getProfile(Player $player){
		return $this->playerProfiles[trim_player_ign($player)];
	}
	public function __construct(ServerAPI &$api, $server = 0){
		self::$instance=$this;
		$this->api =& $api;
		$this->server =& ServerAPI::request();
		$this->mkDirs();
	}
	public function __destruct(){
		
	}
	public function mkDirs(){
		$dir = FILE_PATH."hub/";
		@mkdir($dir);
		$dir .= "players/";
		@mkdir($dir);
		return TRUE;
	}
}
function trim_player_ign($player){
	return strToLower("$player");
}
function hub_require_all(){
	$path = FILE_PATH."plugins/Hub/";
	$dir = dir($path);
	while(($filename = $dir->read()) !== FALSE){
		if(is_file($path.$filename) and substr($filename, -4)==".php")
			require_once($path.$filename);
	}
}
