<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  or @MCPE_modder_for_maps from minecraftforum.net
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class AuthApi{
	private $server;
	private $toRegister = array();
	private $toLogin = array();
	private $loginCnts = array();
	public function init(){
		$config = new Config(FILE_PATH."auth_config.yml", CONFIG_YAML, array(
				"default_allow_IP_auth" => FALSE, // can custom enable
				"login_max_trials" => 5
		));
		define("HUB_AUTH_ALLOW_IP", $config->get("default_allow_IP_auth"));
		define("HUB_AUTH_MAX_TRIALS", $config->get("login_max_trials"));
		Hub::request()->cmdApi->register("auth", "<>", array($this, "cmd"));
		$this->server = ServerAPI::request();
		$this->server->addHandler("player.spawn", array($this, "onSpawn"), PHP_INT_MAX);
		$this->server->addHandler("player.chat", array($this, "c"), PHP_INT_MAX);
	}
	public function onSpawn(&$p){
		$isOld = file_exists(hub_get_player_dir($p->username));
		$p->blocked = TRUE;
		$action = $isOld ? "login" : "register";
		$p->sendChat("Welcome to LegionPE!");
		$cfg = $this->getPlayerConfig($p);
		$arr = $cfg->get("ip");
		$savedArr = $arr;
		$arr[$p->ip] = TRUE;
		$cfg->set("ip", $arr);
		$cfg->save();
		if($cfg->get("do_ip_auth") === TRUE and isset($savedArr[$p->ip])){
			$p->sendChat("You have logged in with your IP.");
			return;
		}
		$p->sendChat("Please $action.");
		if($isOld){
			$toLogin[$p->username] = $p;
			$loginCnts[$p->username] = 0;
			$p->sendChat("You can $action by typing the password you typed in last time directly into chat. Don't worry, the password won't be shown to other players.");
		}
		else{
			$toRegister[$p->username] = $p;
			$p->sendChat("You can $action by typing the password you want to register with directly into chat. Don't worry, the password won't be shown to other players.");
		}
	}
	public function c(&$d){
		$p =& $d["player"];
		$pw = $d["message"];
		if(isset($this->toLogin[$p->username])){
			$ret = $this->checkPw($pw, $p);
			if($ret === TRUE){
				$p->sendChat("You have logged in successfully.");
				$p->blocked = FALSE;
				unset($this->toLogin[$p->username]);
				$this->server->handle("player.auth", $p);
			}
			else{
				$cnt = ++ $this->loginCnts[$p->username];
				if($cnt === HUB_AUTH_MAX_TRIALS){
					$p->sendChat("You have reached the maximum number of attempts to login. You have been kicked for failing to login.");
					$p->close("Login failure");
				}
				$p->sendChat("Password $pw is incorrect. Try again. You have $cnt more chance(s).");
			}
			return FALSE;
		}
		elseif(isset($this->toRegister[$p->username])){
			file_put_contents($this->getFile($p), $this->h($pw));
			$p->sendChat("You have registered successfully. To confirm, your password is $pw.
If you want to change, please contact Lambo or PEMapModder to unregister your account.");
			$p->blocked = FALSE;
			unset($this->toRegister[$p->username]);
			$this->server->handle("player.auth", $p);
			return FALSE;
		}
		else{
			if($this->checkPw($pw, $p) === TRUE){
				$p->sendChat("Don't tell the whole world your password!"); // EXCLUSIVE
				return FALSE;
			}
		}
	}
	public function cmd($cmd, $args, $issuer){
		if(!($issuer instanceof Player))
			return "Please run this command in-game.";
		switch(array_shift($args)){
			case "ip":
				$config = $this->getPlayerConfig($issuer);
				$config->set("do_ip_auth", $this->parseBool($args[0]));
				$config->save();
				return "Set your ip-auth settings to ".($this->parseBool($args[0]) ? "true" : "false");
			default:
				return "Usage: /$cmd <ip> [options...] Auth options setting";
		}
	}
	private function checkPw($pw, $i){
		$i = trim_player_ign($i);
		$h = file_get_contents($this->getFile($i));
		if($this->h($pw) == $h){
			return TRUE;
		}
		return FALSE;
	}
	private function getFile($p){
		return hub_get_player_dir($p).".auth";
	}
	private function h($pw){
		return bin2hex(hash(hash_algos(21), $pw.password_hash($pw, PASSWORD_BCRYPT), TRUE));
	}
	private function parseBool($str){
		$str = strToLower(trim($str));
		$on = array(
				"true", "on", "yes", "ok", "go"
		);
		return in_array($str, $on);
	}
	private function getPlayerConfig($ign){
		return new Config($this->getFile($ign).".cfg", CONFIG_YAML, array(
			"do_ip_auth" => HUB_AUTH_ALLOW_IP,
			"ip" => array ()
		));
	}
}
