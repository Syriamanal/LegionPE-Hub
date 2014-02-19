<?php

/*
Copyright c 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class CmdApi{
	private $server;
	public function __construct(){
		$this->server = ServerAPI::request();
	}
	public function init(){
		$this->server->addHandler("console.command", array($this, "evt"), 49);
		foreach($this->server->api->console->cmds as $cmd => $callback){
			$help = $this->server->api->console->help[$cmd];
			$this->register($cmd, $help, $callback, isset($this->server->api->ban->cmdWhitelist[$cmd]) ? RANKS_GUEST : RANKS_TRUST);
		}
	}
	public function evt($d){
		$this->run($d["cmd"], $d["parameters"], $d["issuer"], $d["alias"]);
	}
	public function register($cmd, $help, $callback, $minPerm){
		$cmd = strToLower(trim($cmd));
		if(!is_callable($callback))return FALSE;
		if(is_array($callback) and ($callback[0] instanceof Minigame)){
			$categ = $callback[0]->getName();
		}
		else $categ = "public";
		$this->cmds[$cmd][$categ] = array(
			"callback" => $callback,
			"usage" => $help,
			"permission" => $minPErm
		);
	}
	public function run(){
		//TODO run a command
	}
}
