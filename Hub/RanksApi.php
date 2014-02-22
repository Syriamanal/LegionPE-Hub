<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

define("RANKS_GUEST", 0);
define("RANKS_TRUST", 0x100);
define("RANKS_CONSOLE", PHP_INT_MAX);
define("RANKS_RCON", PHP_INT_MAX - 2);
class RanksApi{
	private $server;
	public $config;
	public function __construct(){
		$this->server = ServerAPI::request();
	}
	public function init(){
		$this->config = new Config(FILE_PATH."ranks_usernames.yml", CONFIG_YAML, array(
				"VIP_lite" => array("change me in lowercase", "change me"),//dont worry, no IGNs with spaces allowed.
				"VIP_stnd" => array("change me", "change me"),
				"VIP_plus" => array("change me", "change me"),
				"trusted" => array(
					"lambo",
					"spyduck",
					"pemapmodder",
					"xktiverz"
				)
		));
		$this->ranks = new Config(FILE_PATH."ranks_values.yml", CONFIG_YAML, array(
				"console" => RANKS_CONSOLE,
				"rcon - fixed" => RANKS_RCON,
				"VIP_lite" => 0x10,
				"VIP_stnd" => 0x15,
				"VIP_plus" => 0x20,
				"trusted" => RANKS_TRUST
		));
		$this->server->schedule(1200, array($this->config, "reload"));
	}
	public function getPerm($player){
		$ranks = $this->ranks->getAll();
		rsort($ranks, SORT_NUMERIC);
		foreach($ranks as $rank => $v){
			foreach($this->config->get($rank) as $ign){
				if(trim_player_ign($ign) == trim_player_ign($player))
					return $v;
			}
		}
		return FALSE;
	}
}
