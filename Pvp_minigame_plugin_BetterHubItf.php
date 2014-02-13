<?php

/*
__PocketMine Plugin__
class=PvpMinigame
name=PvP Minigame Plugin
author=PEMapModder
version=0
apiversion=11,12
*/

define("PVP_ORIGINAL_WORLD", FILE_PATH."worlds/PvP_original/");
define("PVP_WORLD_TEMP_ONE", FILE_PATH."worlds/PvP_temp1/");
define("PVP_WORLD_TEMP_TWO", FILE_PATH."worlds/PvP_temp2/");
class PvpMinigame implements Plugin, HubInterface{
	public $s, $clients;
	public function __construct(ServerAPI $a,$s=0){
		$this->s=ServerAPI::request();
	}
	public function init(){
		$s=$this->s;
		$s->handle("hub.minigame.register", $this);
		$s->api->level->loadLevel("PvP_original");
		PvpMgUtils::reproduceWorld(1);
		PvpMgUtils::reproduceWorld(2);
		$s->api->cmd->registerCmd("pvp", "pvp kit", $s->api->ranks->getIndex("guest"), array($this, "cmdH"));
		$s->api->cmd->registerCmd("kills", "your total kills", $s->api->ranks->getIndex("guest"), array($this, "cmdH"));
	}
	
	public function __destruct(){
		
	}
	public function getName(){return "PvP";}
	public function getWorldNames(){return array("PvP_temp1","PvP_temp2");}
	public function getMaxPlayers(){return array(100,100);}
	public function getJoinStatus(){
		return array(true, true);
	}
	public function getPlayerList(){
		return $this->clients;
	}
	public function onPlayerJoin(Player $player, $worldName){
		$this->clients[$worldName][$player->username]=$player;
	}
	public function pmPlayerEvt($evt, Player $p, $data=array()){
		switch($evt){
		case "forceQuit":
			unset($this->clients[$p->entity->level->getName()][$p->username]);
			break;
		case "kill":
			$p->sendChat("Your kills +1! Two hearts killing reward! Your points +10! Team points +5!");
			$p->entity->heal(4, "killing reward");
			$pf=MgTool::getProfile($p);
			$pf->addPoints();
			$pf->setMgData($this, "kills", $pf->getMgData($this, "kills")+1);
		}
	}
	public function pmEvt($evt, $data){
		
	}
}
class PvpMgUtils{
	public static function reproduceWorld($id){
		console("[DEBUG] Producing PvP temp world $id");
		if($id==1 and is_dir(PVP_WORLD_TEMP_ONE)){
			return true;
		}
		if($id==2 and is_dir(PVP_WORLD_TEMP_TWO)){
			return true;
		}
		$out=(($id===1)?PVP_WORLD_TEMP_ONE:PVP_WORLD_TEMP_TWO);
		@mkdir($out);
		$in=dir(PVP_ORIGINAL_WORLD);
		console("[NOTICE] Copying from ".PVP_ORIGINAL_WORLD." to $out");
		while(false!==($file=$in->read())){
			if(is_file(PVP_ORIGINAL_WORLD.$file)){
				// console("[DEBUG] Copying file from ".PVP_ORIGINAL_WORLD.$file." to $out".$file);
				file_put_contents($out.$file, file_get_contents(PVP_ORIGINAL_WORLD.$file));
			}
		}
		$chunksIn=PVP_ORIGINAL_WORLD."chunks/";
		$chunksOut=PVP_ORIGINAL_WORLD."chunks/";
		console("Creating chunks directory $chunksOut");
		mkdir($chunksOut);
		console("Created chunks directory $chunksOut");
		$inDir=dir($chunksIn);
		console("Copying chunks.");
		while(false!==($chunk=$inDir->read())){
			if(is_file($chunksIn.$chunk)){
				file_put_contents($chunksOut.$chunk, file_get_contents($chunksIn.$chunk));
			}
		}
		console("Copied chunks.");
		ServerAPI::request()->api->level->loadLevel(substr($out, -10, 9));
		console("[NOTICE] Finished producing and loading temp world $id");
		return true;
	}
}