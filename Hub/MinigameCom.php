<?php

/*
Copyright © 2014 PEMapModder
This software should only be used with prior permission from:
  @PEMapModder from forums.pocketmine.net,
  @PEMapModder from github.com,
  @MCPE_modder_for_maps from minecraftforum.net,
  pemapmodder1970@gmail.com, or
  any players logging into an MCPE server from IP 219.73.81.15 or eycraft.hopto.org
Without permission, you are only expected to view this plugin and not to download AND save it, or to apply it in any PocketMine-MP servers.
*/

class MinigameCom{
	public $hub;
	public $minigames=array();
	public function __construct(Hub $hub){
		$this->hub = $hub;
	}
	public function init(){
		$s = ServerAPI::request();
		$s->addHandler("hub.minigame.register", array($this, "register"), 100);
		$s->addHandler("player.block.touch", array($this, "pmEvt"));
		$s->addHandler("player.chat", array($this, "pmEvt"));
		$s->addHandler("player.move", array($this, "pmEvt"));
		$s->addHandler("player.interact", array($this, "pmEvt"));
		$s->addHandler("player.quit", array($this, "pmEvt"));
		$s->addHandler("player.death", array($this, "pmEvt"));
		$s->addHandler("player.equipment.change", array($this, "pmEvt"));
		$s->addHandler("tile.update", array($this, "pmEvt"));
		$s->addHandler("entity.explosion", array($this, "pmEvt"));
		$s->addHandler("entity.health.change", array($this, "pmEvt"));
		$s->addHandler("item.drop", array($this, "pmEvt"));
	}
	public function register(Minigame $minigame){
		$this->minigame[] = $minigame;
		return $this->hub;
	}
	public function pmEvt($data, $evt){
		$s = ServerAPI::request();
		$pa = $s->api->player;
		$ea = $s->api->entity;
		switch($evt){
			case "player.block.touch":
				return $this->bpEvt($data["type"] == "break" ? "destroyBlock" : "useItem", $data["player"], array("item"=>$data["item"], "target"=>$data["target"]));
			case "player.chat":
				return $this->bpEvt("chat", $data["player"], $data["message"]);
			case "player.move":
				return $this->bpEvt("move", $data["player"]);
			case "player.interact":
				$attacker = $pa->getByEID($data["entity"]->eid);
				$victim = $pa->getByEID($data["target"]->eid);
				$e0 = $this->bpEvt("attack", $attacker, $data["target"]) === FALSE;
				$e1 = FALSE;
				if($victim instanceof Player)
					$e1 = $this->bpEvt("attacked", $victim, $attacker) === FALSE;
				$r = $e0 or $e1;
				return !$r;
			case "player.quit":
				return $this->bpEvt("quit", $data);
			case "player.death":
				$victim = $data["player"];
				$killer = $pa->getByEID($data["cause"]);
				$e0 = $this->bpEvt("die", $victim, $data["cause"]) === FALSE;
				$e1 = FALSE;
				if($killer instanceof Player)
					$e1 = $this->bpEvt("kill", $killer, $victim) === FALSE;
				$r = $e0 or $e1;
				return !$r;
			case "player.equipment.change":
				return $this->bpEvt("carryItem", $data["player"], $data["item"]);
			case "tile.update":
				return $this->bpEvt("placeSign", $data->data["creator"], $data);
			case "entity.explosion":
				return $this->bEvt("explode", $data->level, array("pos"=>$data["source"], "size"=>$data["size"]));
			case "entity.health.change":
				$victim = $pa->getByEID($data["eid"]);
				$cause = $pa->getByEID($data["cause"]);
				$old = $data["entity"]->getHealth();
				$new = $data["health"];
				$type = (($old > $new) ? "harm" : "heal");
				$e0 = FALSE;
				if($victim instanceof Player)
					$e0 = $this->bpEvt($type."ed", $victim, array("cause" => $data["cause"], "delta" => abs($old - $new)));
				$e1 = FALSE;
				if($cause instanceof Player)
					$e1 = $this->bpEvt($type, $cause, array("target" => $data["entity"], "delta" => abs($old - $new)));
				$r = $e0 or $e1;
				return !$r;
			case "item.drop":
				return $this->bEvt("dropItem", $data["level"], array("pos"=>(new Position($data["x"], $data["y"], $data["z"], $data["level"])), "item"=>$data["item"]));
		}
	}
	protected function bpEvt($event, Player $player, $data=array()){
		
	}
	protected function bEvt($event, Level $level, $data=array()){
		
	}
}
