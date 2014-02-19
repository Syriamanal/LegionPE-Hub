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
		$this->run($d["cmd"], $d["parameters"], $d["issuer"]);
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
			"permission" => $minPerm
		);
	}
	public function run($cmd, $params, $issuer = "console"){
		if($cmd == "help"){
			if($issuer instanceof Player)
				$issuer->sendChat($this->help($params, $issuer));
			else console(FORMAT_GREEN."[CMD] ".$this->help($params, $issuer));
		}
		if(!isset($this->cmds[$cmd]))
			return $this->send("There is no such command /$cmd");
		$c = $this->getCateg($issuer);
		if(!isset($this->cmds[$cmd][$c])){
			if(isset($this->cmds[$cmd]["public"]))
				$c = "public";
			else return $this->send("You cannot use this command in this minigame/area.");
		}
		$d = $this->cmds[$cmd][$c];
		$p = $d["permission"];
		if($p <= Hub::request()->ranksApi->getPerm($issuer))
			$this->send(call_user_func($callback, $cmd, $params, $issuer), $issuer);;
		else $this->send("You don't have permission to use this command /$cmd in this minigame/area.");
	}
	public function send($msg, $rcpt){
		if($rcpt instanceof Player)
			return $rcpt->sendChat($msg);
		return console("[CMD] $msg");
	}
	public function getCateg($issuer){
		$categ = (($issuer instanceof Player) and (($mg = Hub::request()->com->getMinigameByLevel($issuer->level)) instanceof Minigame));
		return $categ ? $mg->getName() : "public";
	}
}
