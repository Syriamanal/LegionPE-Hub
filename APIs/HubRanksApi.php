<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_modder_for_maps at http://minecraftforum.net
*/

class HubRanksApi{
	public $db, $file;
	public function __construct(){
	}
	public function init(){
		console("[INFO] Loading ranks API");
		@mkdir(FILE_PATH."hub/");
		$this->file=FILE_PATH."hub/ranks.yml";
		$this->db=new Config($this->file, CONFIG_YAML, array("readme"=>"Repeated names will be ignored. Only first time will be read. Also, remember to write in lowercase.", "ranks"=>array("staffs"=>array("lambo", "spyduck", "pemapmodder", "mcpesrccode"), "vip starred"=>array("vip starred one", "vip starred two"), "vip plus"=>array("vip plus one", "vip plus two"), "vip standard"=>array("vip std 1", "vip std 2"))));
	}
	public function __destruct(){
		@$this->db->save();
	}
	public function getPlayerRank($p){
		if(strtolower($p)==="console" or strtolower($p)==="rcon"){//ban @console and @rcon
			return $p;
		}
		if(!isset($this->db))$this->init();
		foreach($this->db->get("ranks") as $rank=>$igns){
			if(in_array(strtolower("$p"), $igns))
				return $rank;
		}
		return "guest";
	}
	public function getIndex($rankName,$suppressWarnings=false){
		$rank=strtolower(trim($rankName, "\t\n\r -_.,+\\/\0\"\'s"));//remove connectors and multiples
		switch($rank){
			case "guet":
				return 0;
			case "viplite":
				return 1;
			case "vip":case "vipnorm":
				return 2;
			case "vipplu":
				return 3;
			case "viptarred":case "viptar":
				return 4;
			case "trut":case "taff":
				return 5;
			case "rcon":
				return 6;
			case "console":
				return 7;
		}
		if(!$suppressWarnings)
			console(FORMAT_RED."[WARNING] Trying to get index of undefined rank $rankName. Boolean false will be returned. ".
					FORMAT_YELLOW."Caution: Bugs will occur.");
		return false;
	}
	public function getPlayerRankIndex($p){
		$p="$p";
		return $this->getIndex($this->getPlayerRank);
	}
}
