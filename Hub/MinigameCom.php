<?php

class MinigameCom{
	public $hub;
	public function __construct(HubPlugin $hub){
		$this->hub = $hub;
	}
}
