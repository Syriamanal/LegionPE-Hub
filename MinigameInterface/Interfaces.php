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
	public function getPlayerList();
	public function onPlayerJoin(Player $player, $worldName);
	public function pmPlayerEvt($evt, Player $player, $data);
	public function pmEvt($evt, $data);
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
}

class PlayerProfile extends Config{
	public $player;
	public function __construct(Player $p, $filename="profile"){
		$path=FILE_PATH."players_databases/".strtolower($p->username[0])."/";
		@mkdir($path);
		$path.=strtolower("$p")."/";
		@mkdir($path);
		parent::__construct(FILE_PATH."players_databases/".strtolower($p->username[0])."/".strtolower("$p")."/$filename.yml", CONFIG_YAML);
		$this->player=$p;
		$this->addConstructs();
		if($filename=="profile"){
			$this->set("team", MgTool::getTeam(p));
			$this->set("points", 0);
		}
	}
	private function addConstructs(){
		if(!is_numeric($this->get("constructs"))){
			$this->set("constructs", 0);
			return false;
		}
		$this->set("constructs", $this->get("constructs")+1);
	}
	public function getFile(){
		return $this->file;
	}
	public function setMgData(HubInterface $hi, $name, $data){
		$d=$this->get($hi->getName());
		$d["$name"]=$data;
		$this->set($hi->getName(), $d);
	}
	public function getMgData(HubInterface $hi, $name){
		return $this->get($hi->getName())["$name"];
	}
	public function getMgDataAll(HubInterface $hi){
		return $this->get($hi->getName());
	}
	public function addPoints($points=10){
		$pts=$this->get("points");
		$pts+=$points;
		$this->set("points", $pts);
	}
	public function takePoints($points=10){
		$this->addPoints($points*-1);
	}
	public function getPoionts(){
		return $this->get("points");
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
