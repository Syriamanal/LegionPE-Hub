<?php

/*
__PocketMine Plugin__
class=PvpMinigame
*/

class PvpMinigame implements Plugin, HubInterface{
	public function __construct(ServerAPI $a,$s=0){}
	public function init(){
		$this->s=ServerAPI::request();
		$s=$this->s;
		$s->handle("hub.minigame.register", $this);
	}
	public function __destruct(){
		
	}
	public function getName(){return "PvP";}
	public function getWorldNames(){return array("pvp_1","pvp_2");}
	public function getMaxPlayers(){return array(100,100);}
	public function getJoinStatus(){
		
	}
	public function getPlayerList(){
		
	}
	public function pmPlayerEvt($evt, Player $p, $data){
		
	}
}
