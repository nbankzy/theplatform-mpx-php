<?php
require_once('Mpx.php');
class MpxUser extends Mpx {
	protected $_userId;
	protected $_username;

	/**
    * @param string  $username
    * @param string  $password
    * @return object user account info
    * @link http://help.theplatform.com/display/wsf2/signIn+method
    */
	public function signIn($username, $password) {
		// Set username in parent class
		$this->_username = $username;

		$signInUrl = 'https://identity.auth.theplatform.com/idm/web/Authentication/signIn';
		$data = array(
			"schema" => "1.0",
			"form" => "json",
			"username" => $username,
			"password" => $password
		);

		$response = $this->_callAPI('GET', $signInUrl, $data);
		// Set token
		parent::$_token = $response->body->signInResponse->token;
		// Set User ID
		$this->_userId = $response->body->signInResponse->userId;

		return $response->body->signInResponse;
	}
	
	/**
    * @return object empty response object
    * @link http://help.theplatform.com/display/wsf2/signOut+method
    */
	public function signOut() {
		$signOutUrl = 'https://identity.auth.theplatform.com/idm/web/Authentication/signOut';

		$data = array(
			"schema" => "1.0",
			"form" => "json",
			"token" => parent::$_token,
		);

		$signOutResponse = $this->_callAPI('GET', $signOutUrl, $data);

		return json_decode($signOutResponse);
	}

	/**
    * @return string user session token
    */
	public function getToken() {
		return parent::$_token;
	}

}

?>