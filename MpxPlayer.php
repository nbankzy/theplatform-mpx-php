<?php
class MpxPlayer extends Mpx{
	private $playerEndpoint = 'http://data.player.theplatform.com/player/data/Player';

	public function getPlayers() {
		// Media url parameters
		$urlParams = array(
			"schema" => "1.3",
			"form" => "cjson",
			"token" => parent::$_token,
			"account" => parent::$_account
		);
		$response = $this->_callAPI('GET', $this->playerEndpoint, $urlParams);
		return $this->_responseCode($response);
	}
}