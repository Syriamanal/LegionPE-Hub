<?php

/*
Copyright c 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  @MCPE_modder_for_maps from minecraftforum.net,
  pemapmodder1970@gmail.com, or
  any players logging into an MCPE server from IP 219.73.81.15 or eycraft.hopto.org
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

abstract class Minigame implements Plugin{ // Hub requires data from Minigame
	private $api, $server, $hub;
	public function __construct(ServerAPI $api){ // Override and extend this method
		//subclasses must still use the parameters in the Plugin interface constructor
		$this->api = $api;
		$this->server = ServerAPI::request();
	}
	public function init(){
		$this->hub = $this->server->dhandle("hub.minigame.register");
	}
	public abstract function __destruct();
	public abstract function playerEvent($event, Player $player, $data=array());
	/**
	 * invoked when a non-player-related (or at least not directly) event occurs in one of the levels 
	*/
	public abstract function event($event, $data);
	/**
	 * invoked when a player is sent to this minigame
	 * @param Player $player the player trying to enter the minigame
	 * @param Tournament $tournament the tournament the player is trying to enter
	*/
	public abstract function onPlayerJoin(Player $player, Tournament $tournament);
	/**
	 * invoked when a player is forced to leave a game
	 * @param Player $player the player to force-leave
	*/
	public abstract function onPlayerLeave(Player $player);
	//////////////////////////////////GETTERS//////////////////////////////////
	/**
	 * @return string minigame name
	*/
	public abstract function getName();
	/**
	 * @return string[] an array of names of all worlds this minigame uses, including tournament usages
	*/
	public abstract function getAllWorldsNames();
	/**
	 * @return string[] an array of names of all players in this minigame
	*/
	public abstract function getAllPlayers();
	/**
	 * @return Tournament[] all tournaments
	*/
	public abstract function getAllTournaments();
}
/*
**DUMMY** __PocketMine*Plugin__ **DUMMY**
**DUMMY** class=DummyMinigame **DUMMY**
**DUMMY** name=DummyMinigame **DUMMY**
**DUMMY** author=DummyMinigame **DUMMY**
**DUMMY** apiversion=12,*13 **DUMMY**
**DUMMY** version=DummyVersion **DUMMY**
*/
class DummyMinigame extends Minigame{
	public function __construct(ServerAPI $api){
		parent::__construct($api); // preset everything including $this->api and $this->server
	}
	public function init(){
		parent::init(); // get $this->hub, as well as register this minigame
		//inherit abstract function
	}
	public function __destruct(){
		//inherit abstract function
	}
}