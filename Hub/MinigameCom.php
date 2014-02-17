<?php

class MinigameCom{
	public $hub;
	public function __construct(HubPlugin $hub){
		$this->hub = $hub;
	}
	public function init(){
		$s = ServerAPI::request();
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
		$s->addHandler("console.command", array($this, "pmEvt"));
		$s->addHandler("item.drop", array($this, "pmEvt"));
	}
	public function pmEvt($data, $evt){
		switch($evt){
			
		}
	}
}
