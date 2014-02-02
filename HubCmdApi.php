<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

class HubCmdApi{
	public $cmds=array();
	public function __construct(){
	}
	public function init(){
		ServerAPI::request()->addHandler("server.start", array($this, "initialize"), 101);
	}
	public function __destruct(){
		
	}
	public function initialize(){
		$c=($s=ServerAPI::request())->api->console;
		foreach($c->cmds as $cmd=>$callback)
			if($cmd==="help")$cb=array($this,"helper");//idk if this will change the default. (It doesn't matter though, I just realized)
			else $cb=$callback;
			$this->registerCmd($cmd, $c->help[$cmd],
					((isset($s->api->ban->cmdWhitelist[$cmd]) and $s->api->ban->cmdWhitelist[$cmd]===true)?
						$s->api->ranks->getIndex("guest"):
						$s->api->ranks->getIndex("trust")
					), $cb);
		$this->sortAlpha();
		$s->addHandler("console.command", array($this,"cmdRed"), 3);
	}
	public function cmdRed($data){
		$cmd=$data["cmd"];
		$args=$data["parameters"];
		$player=$data["issuer"];
		if(isset($this->cmds[$cmd])){
			$d=$this->cmds[$cmd];
			$mg=HubMasterPlugin::get()->getPlayerMinigame($player);
			if($mg instanceof HubInterface){
				$d=$d[$mg->getName()];
				if ($d[1] <= ServerAPI::request()->api->ranks->getPlayerRankIndex($player)){
					$send=$this->run($cmd, $args, $player, $d[2]);
					if(is_string($send) and $send!=""){
						if($player instanceof Player)
							$player->sendChat($send);
						else
							console("[HUB_CMD] $send");
					}
				}
				else{
					$player->sendChat("You don't have permission to use the command /$cmd!");
					//ServerAPI::request()->api->security->logAnnoymous("$player tried to run /$cmd ".implode(" ",$args),"command-related","CommandAPI");
				}
			}
		}
		else{
			if(!($player instanceof Player))
				console("Command /$cmd not found");
			else $player->sendChat("Command /$cmd not found");
		}
		return false;
	}
	public function run($cmd,$args,$issuer,$callback){
		return $callback($cmd,$args,$issuer,$cmd);//stick to the conventional parameters
	}
	public function registerCmd($c, $desc, $minPower, $callback){
		$this->cmds[$c][((is_array($callback) and $callback[0] instanceof HubInterface)?
				$callback[0]->getName():
				"public")]
			=array($desc, $minPower, $callback);
	}
	public function helper($c, $a, $asker){
		if(isset($a[0]) and !is_numeric($a[0]){// no numeric commands
			$cmd=$a[0];
			if(!isset($this->cmds[$cmd])) return "Command /$cmd not found in help list.";
			$data=$this->cmds[$cmd];
			$output="";
			if(!($asker instanceof Player) or $asker->entity->level->getName()==="world"){
				foreach($data as $category=>$info)
					if($info[1] <= ServerAPI::request()->api->ranks->getPlayerRankIndex($asker))
						$output.=($category==="public"?"":"In minigame $category,\n    ")."Usage: /$cmd ".$info[0]."\n";
			}
			else{
				$category=HubMasterPlugin::get()->getPlayerMinigame($asker)->getName();
				if($data[$category][1] <= ServerAPI::request()->api->ranks->getPlayerRankIndex($asker))
					$output.="Usage: /$cmd ".$data[$category][0];
			}
			return $output;
		}
		if(!isset($a[0]))$page=1;
		else $page=$a[0];
		$cmdList=array();
		foreach($this->cmds as $cmd=>$categs){
			
		}
	}
	public function sortAlpha(){
		ksort($this->cmds, SORT_NATURAL|SORT_FLAG_CASE);
	}
}
