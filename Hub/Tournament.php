<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

abstract class Tournnament{
	private $minigame, $id, $worldName;
	public function __construct(Minigame $minigame, $id, $cloneWorld, $params = array()){
		$this->minigame = $minigame;
		$this->id = $id;
		$this->worldName = $cloneWorld;
	}
	public function init(){
		$wn = FILE_PATH."worlds/".$this->worldName;
		$this->copyDir($wn."/", $wn."_temp_".$this->id."/");
		$this->worldName = $wn."_temp_".$this->id."/";
		ServerAPI::request()->api->level->loadLevel($this->worldName);
	}
	public function __destruct(){
		$path = explode("/", $this->worldName);
		$wn = $path[count($path) - 1];
		ServerAPI::request()->api->level->unloadLevel($wn);
		unlink($this->worldName);
	}
	public function copyDir($old, $new){
		$dir = dir($old);
		while(($fn = $dir->read()) !== false){
			if(is_file($old.$fn)){
				file_put_contents($new.$fn, file_get_contents($old.$fn));
			}elseif(is_dir($old.$fn) and strpos($fn, ".") === false)//TODO improve
				$this->copyDir($old.$fn, $new.$fn);
		}
		return true;
	}
	public function getMinigame(){
		return $this->minigame;
	}
	public function getId(){
		return $this->id;
	}
	public function getWorldNames(){
		if(is_string($this->worldName))
			return array($this->worldName);
		return $this->worldName;
	}
	public abstract function playerReceiver(Player $player);
	/**
	 * @return int the max number of players to display on hub signs
	*/
	public abstract function getMaxPlayers();
	/**
	 * @return string[] an array of names of all players in this tournament
	*/
	public abstract function getPlayerNames();
}
