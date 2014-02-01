<?php

class HubData{
	public static $pvpSignsCoordsStart=new Position(
				125, 31, 100, ServerAPI::request()->api->level->get("world"));
	public static $pvpSignsCoordsEnd=new Position(
				133, 33, 100, ServerAPI::request()->api->level->get("world"));
	public static $spleefSignsCoordsStart=new Position(
				100, 33, 133, ServerAPI::request()->api->level->get("world"));
	public static $spleefSignsCoordsEnd=new Position(
				100, 33, 125, ServerAPI::request()->api->level->get("world"));
	public static $spawnCoords=new Position(
				129, 32, 129, ServerAPI:: request ()->api->level->get ("world"));
}
