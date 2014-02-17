<?php

/*
__PocketMine Plugin__
class=HubPlugin
name=LegionPE Hub
version=beta 1.0
apiversion=12
author=PEMapModder
*/

hub_require_all();
class HubPlugin implements Plugin{
	private $api, $server;
	public $mgCom, $pmCom;
	public $playerProfiles = array();
	public function init(){
		$this->mgCom = new MinigameCom($this);
		$this->mgCom->init();
		$this->initEvents();
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
	public function __construct(ServerAPI &$api, $server = 0){
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
