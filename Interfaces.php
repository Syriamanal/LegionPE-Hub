<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

interface HubInterface{
	public function getName();
	public function getWorldNames();//below counts must be equal to this count
	public function getMaxPlayers();
	public function getJoinStatus();
	public function getPlayersList();
	public function pmPlayerEvt($evt, Player $player, $data);
	public function cmdHandler($cmd, $args, $issuer);
	public function __toString();//return getName()
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
		$this->addConstructs();
	}
	public function addConstructs(){
		if(!is_numeric($this->get("constructs"))){
			$this->set("constructs", 0);
			return false;
		}
		$this->set("constructs", $this->get("constructs")+1);
	}
	public function getFile(){
		return $this->file;
	}
	public function setMinigameData(HubInterface $hi, $name, $data){
		$d=$this->get($hi->getName());
		$d["$name"]
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
