<?php

/*
Copyright  © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

abstract class Minigame implements Plugin{ // Hub requires data from Minigame
	private $api, $server, $hub;
	public function __construct(ServerAPI $api, $server = FALSE){ // Override and extend this method
		//subclasses must still use the parameters in the Plugin interface constructor
		$this->api = $api;
		$this->server = ServerAPI::request();
	}
	public function init(){
		$this->hub = $this->server->dhandle("hub.minigame.register");
	}
	public abstract function __destruct();
	public abstract function playerEvent($event, Player $player, $data=array());
	protected function startTournament($id, $baseWorld, $tClass, $options = array()){
		$this->tournaments[$id] = new $tClass($this, $id, $baseWorld, $options);
		$this->tournaments[$id]->init();
	}
	/**
	 * invoked when a non-player-related (or at least not directly) event occurs in one of the levels 
	*/
	public abstract function event($event, $data = array());
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
	public function __construct(ServerAPI $api, $server = FALSE){
		parent::__construct($api); // preset everything including $this->api and $this->server
	}
	public function init(){
		parent::init(); // get $this->hub, as well as register this minigame
		//inherit abstract function
	}
	public function __destruct(){
		//inherit abstract function
	}
	public function playerEvent($evt, Player $plyr, $data = array()){
	}
	public function event($evt, $data = array()){
	}
	public function onPlayerJoin(Player $plyr, Tournament $tnmt){
	}
	public function onPlayerLeave(Player $plyr){
	}
	public function getName(){
		return "Dummy Minigame";
	}
	public function getAllWorldsNames(){
		return array();
	}
	public function getAllTournaments(){
		return array();
	}
	public function getAllPlayers(){
		return array();
	}
}
