<?php

/*
Copyright © PEMapModder 2014
This software can only be used with prior permission from @PEMapModder at https://github.com or http://forums.pocketmine.net, or from @MCPE_moodder_for_maps at http://minecraftforum.net
*/

class HubTeamApi{
	public $teams;
	public function init(){
		ServerAPI::request()->schedule(300, array($this, "updateChart"), array(), true);
		
	}
	public function getRelPoints($team){
		
	}
	public function updateChart(){
		for($i=0; $i<4; $i++)
			LegionPEData::setChart($i, $this->getRelPoints($i)*40);
	}
}
class TeamData extends Config{
	public function __construct($team){
		@mkdir(FILE_PATH."hub/");
		@mkdir(FILE_PATH."hub/teams/");
		parent::__construct(FILE_PATH."hub/teams/".$this->getTeamName($team).".yml", CONFIG_YAML);
	}
	public function addPoints($points=5){
		$this->set("points", $this->getPoints()+$points);
	}
	public function getPoints(){
		return $this->get("points");
	}
}