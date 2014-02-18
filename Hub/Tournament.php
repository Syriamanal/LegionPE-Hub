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
	public function __construct(Minigame $minigame, $id, $worldName){
		$this->minigame = $minigame;
		$this->id = $id;
		$this->worldName = $worldName;
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
