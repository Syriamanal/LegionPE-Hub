<?php

/*
name=CommandsHandler
author=PEMapModder
version=1
apiversion=11,12
class=HubCmdApi
*/

class HubCmdApi implements Plugin{
	public $cmds=array();
	public function __construct (ServerAPI $a, $s=0){}
	public function __destruct(){}
	public function init(){
		ServerAPI:: request ()->addHandler("server.start", array($this, "initialize"), 101);
	}
	public function initialize(){
		$c=ServerAPI::request()->api->console;
		$c->register("help", "[page] Help page on commands ", array($this, "helper"));
		foreach($c->cmds as $cmd=>$callback)
			$this->registerCmd($cmd, $c->help[$cmd], false, $callback);
		$this->sortAlpha();
	}
	public function registerCmd($c, $desc, $isWl, $callback){
		$this->cmds[$c][($callback instanceof HubInterface ? $callback->getName() : "public")]=array($desc, $isWl, $callback);
	}
	public function helper($c, $a, $asker){
		if(isset($a[0]) and !is_numeric($a[0]){// no numeric commands
			$cmd=$a[0];
			if(!isset($this->cmds[$cmd])) return "Command /$cmd not found in help list.";
			$data=$this->cmds[$cmd];
			$output="";
			if(!($asker instanceof Player) or $asker->entity->level->getName()==="world"){
			foreach($data as $category=>$info){
				$output.=($category==="public"?"":"In minigame $category,\n    ")."Usage: /$cmd ".$info[0]."\n";
			}}
		else $output.="Usage: /$cmd ".$data[[TODOTODOTODOTODOTODOTODO]][0];
		return $output;
		}
		$page=(isset($a[0])?$a[0]-:0);
		
	}
	public function sortAlpha(){
		
	}
}
