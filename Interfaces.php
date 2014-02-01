<?php
/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

interface HubInterface{
	public function getName();
	public function getMaxPlayers();
	public function getJoinStatus();
	public function getWorldNames();
	public function getPlayersList();
	public function pmPlayerEvt($evt, Player $player, $data);
}

interface MinigameTournament{
	
}

class MgTool{//static
	public static function getTeam($p){
		return $this->getProfile($p)->get("team");
	}
	public static function getProfile($p){
		return HubMasterPlugin::get()->getProfile("$p");
}

class PlayerProfile extends Config{
	public $player;
	public function __construct(Player $p, $filename="profile"){
		parent::__construct(FILE_PATH."players_databases/".strtolower(substr("$p", 0, 1))."/".strtolower("$p")."/$filename.yml", CONFIG_YAML, array());
		$this->player=$p;
	}
	public function getFile(){
		return $this->file;
	}
}

class PlayerAssistant{
	//should this be extended?
	//^_^ Forgot what I planned to make with this
	public $client;
	public function __construct(Player $client){
		$this->client=$client;
	}
	////////////////////
	////fast-forward////
	////////////////////
	public function getX(){
		return $this->client->entity->x;
	}
	public function getY(){
		return $this->client->entity->y;
	}
	public function getZ(){
		return $this->client->entity->z;
	}
	public function tell($msg){
		$this->client->sendChat($msg);
	}
	public function __toString(){
		return $this->client->username;
	}
}
