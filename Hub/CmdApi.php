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
	}
	public function evt($d){
		$this->run($d["cmd"], $d["parameters"], $d["issuer"], $d["alias"]);
	}
	
}
