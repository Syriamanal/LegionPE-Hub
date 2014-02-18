<?php

/*
Copyright c 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  @MCPE_modder_for_maps from minecraftforum.net,
  pemapmodder1970@gmail.com, or
  any players logging into an MCPE server from IP 219.73.81.15 or eycraft.hopto.org
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

abstract class Tournnament{
	private $minigame, $id, $worldName;
	public function __construct(Minigame $minigame, $id, $worldName){
		$this->minigame = $minigame;
		$this->id = $id;
		$this->worldName = $worldName;
	}
	public function getMinigame(){
		return $this->minigame;
	}
	public function getId(){
		return $this->id;
	}
	public function getWorldName(){
		if(is_string($this->worldName))
			return array($this->worldName);
		return $this->worldName;
	}
}
