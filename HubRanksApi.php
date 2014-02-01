<?php

class HubRanksAPI{
	public $db, $file;
	public function init (){
		console ("[INFO] Loading ranks API");
		$this->file=FILE_PATH."hub/ranks.yml";
		$this->db=new Config($this->file, CONFIG_YAML, array("readme"=>"Repeated names will be ignored. Only first time will be read. Also, remember to write in lowercase.", "ranks"=>array("staffs"=>array("lambo", "spyduck"), "vip starred"=>array("vip starred one", "vip starred two"), "vip plus"=>array("vip plus one", "vip plus two"), "vip standard"=>array("vip std 1", "vip std 2"))));
	}
	public function getPlayerRank($p){
		foreach($this->db->get("ranks") as $rank=>$igns){
			if(in_array(strtolower("$p"), $igns))
				return $rank;
		}
		return "guest";
	}
}
