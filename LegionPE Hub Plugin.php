<?php
/*
author=PEMapModder
name=LegionPE Hub Plugin
version=0.0.0
apiversion=11,12
class=LegionPEHubMainPlugin
*/
class LegionPEHubMainPlugin implements Plugin{
 public function __construct(ServerAPI $a, $s=0){}
 public function init(){
  $s=ServerAPI::request();
  $s->addHandler("hub.register.minigame", array($this, "minigameItf"));
  $s->addHandler("player.connect", array($this, "pocketmineItf"));
  $s->addHandler("player.spawn", 
 }
 public function __destruct(){
  
 }
 
 public function minigameItf(HubInterface $hi){
  
 }
}
interface HubInterface{
 public function onEnter(Player $p);
 public function isConnectable();
 public function getTournaments();
 public function onForceKick();
 public function pmEvt($data, $evt);
}
interface LegionPETournament{
 public function getMaxPlayers();
 public function canDirectEnter();
 public function pmEvt($data, $evt);
}






