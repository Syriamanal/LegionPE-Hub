<?php
/*
__PocketMine Plugin__
name=PvpMgPlugin
author=PEMapModder
version=1.0
apiversion=11,12
class=PvpHubInterface
*/
class TooSimpleKillCounter implements Plugin{
	private $players=array(), $plusPlayers=array(), $normHeal, $plusHealExtra, $vips=array(), $vipPlus=array (), $vipStars=array(), $staffs=array ();
	public function __construct (ServerAPI $a, $s=0){}
	public function init (){
	 	$s=ServerAPI::request ();$config=new Config(
ServerAPI::request()->api->plugin->configPath($this)."config.yml", CONFIG_YAML,
array(
	"IO interval in seconds"=>300,
	"killing reward heal (normal players)"=>2,
	"killing reward heal (VIP)"=>4),
	"VIPs or trust or owners in lower case"=>array());
	 	$t=$s->get("IO interval in seconds");
	 	$s->schedule ($t*20, array($this, "ioAll");
	 	$nh=$config->get("killing reward heal (normal players)");
	 	$this->plusHealExtra=$config->get("killing reward heal (VIP)")-$nh;
	 	$this->normHeal=$nh;
	 	$s->plusPlayers=$config->get("VIPs or trust or owners in lower case");
	}
	public function __destruct(){
	 	$this->ioAll();
	}
	public function ioAll(){
	 	$cnt++;
	 	foreach($this->players as $p=>$pk){
	 	 	@file_put_contents($this->getPlayerFile($p), $pk);
	 	 	if(!$this->isOnline($p))unset($this->players[$p]);
	 	 	$cnt++;
	 	}
	 	console("[DEBUG] Saved $cnt player databases.");
	}
	public function pvpCmd($c, $a, $i){
	 	$this->equip($i);
	}
	public function killsCmd($c, $a, $i){
	 	return (($i instanceof Player)?("Your kills: ".($this->players["$i"]):"Please run this command in-game.");
	}
	public function onSpawn($d){
	 	$f=$this->getPlayerFile("$d");
	 	if(file_exists($f)){
	 	 	$c=file_get_contents($f);
	  	 	if(is_numeric($c)){
		 	 	$this->players["$d"]=(int)$c;
 		 	 	return;
	 	 	}
	 	 	console("[WARNING] $d's database is corrupted. Regenerating his database.");
	 	 	$d->sendChat("Your database is corrupted. Regenerating and resetting your database. Our apologies.");
	 	}
	 	else console("[DEBUG] $d database not found. Generating database ");
	 	file_put_contents($f, "0");
	 	$this->players["$d"]=0;
	}
	public function onKill($d){
	 	$p=ServerAPI::request()->api->player->getByEID($d["cause"]);
	 	if(!($p instanceof Player)) return;
	 	$p->sendChat("Your kills +1");
	 	$p->sendChat("Your current kills: ".(++$this->players("$p"));
	 	$p->heal($this->normHeal, "killing reward");
	 	if(in_array(strtolower($p->username), $this->plusPlayers))$p->heal($this->plusHealExtra, "plus player killing reward");
	}
	public function onChat($d){
	 	$p=$d["player"];
	 	$msg=$d["message"];
	 	$kills=$this->players ["$p"];
	 	$tag="newbie";
	 	//ascending order
	 	if($kills>=25)$tag="";
	 	if($kills>=50)$tag="Fighter";
	 	if($kills>=100)$tag="Warrior";
	 	if($kills>=2000)$tag="Super addict";
	 	if(in_array("$d", $this->plusPlayers))$tag="VIP][$tag";
	 	if(in_array(strtolower("$d"), array("lambo", "spyduck"))$tag="***OWNER of LegionPE***";
	 	if("$d"=="PEMapModder") $tag="***PLUGIN DEV of LegionPE***";
	 	ServerAPI::request()->api->chat->broadcast(($tag==""?"":"[$tag]")."[$kills] $p: $msg");
	}
	public function equip($d){// we should get a balance. i hate it when people bully with money... consider vip and vip+ and vip★?
	 	$sword=267;
	 	$helm=306;
	 	$food=297;
	 	if(in_array("$d", $this->plusPlayers)){
	 	 	$sword=276;
	 	 	$helm=310;
	 	 	$food=400;//heal faster
	 	}
	 	$d->addItem($food, 0, 32);
	 	$d->addItem($sword, 0, 1);
	 	for($base=0;$base<4;$base++)$d->setArmorSlot($base, BlockAPI::getItem($helm+$base));
	}
	public function onItemDrop($d){
	 	if (strtolower($d["level"]->getName())=="pvp") return false;
	}
	function isOnline($ign){
	 	return ServerAPI::request()->api->player->get("$ign", false)!==false;
	}
	// TODO implement more ranks of VIP
	public function getPlayerRank($p){
	 	$p=strtolower("$p");
	 	if(in_array($p, $this->staffs)) return "staff";
	 	if(in_array($p, $this->vipStars)) return "vip.star";
	 	if(in_array($p, $this->vipPlus)) return "vip.plus";
	 	if(in_array($p, $this->vips)) return "vip";
	 	return "guest";
	}
	public function getPlayerFile($p){
	 	$name=strtolower("$p");
	 	$dir=FILE_PATH."TooSimpleKillCounter/";
	 	@mkdir($dir);
	 	$dir.="players_databases/";
	 	@mkdir($dir);
	 	$dir.=substr($name, 0, 1)."/";
	 	@mkdir($dir);
	 	return $dir."$name.txt";
	}
}

class PvpHubInterface implements HubInterface, Plugin{
	private $p;
	public function __construct(ServerAPI $a, $s=0){
		$this->p=new TooSimpleKillCounter($a);
	}
	public function __destruct (){
		$this->p->__destruct();
	}
	public function init (){
		ServerAPI::request()->handle("hub.minigame.register", $this);
		$this->p->init();
	}
	public $clnts;
	public function receivePlayerJoin(Player $p){
		return $this->p->onSpawn($p);
	}
	public function getTnmtsCount(){
		return 1;
	}
	public function getName(){
		return "PvP";
	}
	public function getWorldNames(){
		return array("pvp");
	}
	public function getMaxPlayers(){
		return 999;
	}
	public function getPlayersList(){
		return $this->clnts;
	}
	public function getJoinStatus(){
		return "JOIN";
	}
	public function pmPlayerEvt($evt, Player $player, $data){
		
	}
	public function cmdHandler($cmd, $args, $issuer){
	switch($cmd){
	case "pvp":
		
	break;case "kills":
		
	break;
	}
	}
	public function registerCmds($com){
		$com->registerCmd("pvp", "pvp kit", true);
		$com->registerCmd("kills", "your total kills", true);
	}
}
