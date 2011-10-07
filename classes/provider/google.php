<?php

namespace OAuth2;

class Provider_Google extends Provider {  
	
	public $name = 'google';

	public $uid_key = 'uid';
	
	public $scope = '';

	public function url_authorize()
	{
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function url_access_token()
	{
		return 'https://accounts.google.com/o/oauth2/token';
	}

	public function get_user_info($token)
	{
		$url = 'https://graph.facebook.com/me?'.http_build_query(array(
			'access_token' => $token,
		));

		$user = json_decode(file_get_contents($url));

		// Create a response from the request
		return array(
			'nickname' => $user->username,
			'name' => $user->name,
			'email' => $user->email,
			'location' => $user->hometown->name,
			'description' => $user->bio,
			'image' => 'http://graph.facebook.com/'.$user->id.'/picture?type=normal',
			'urls' => array(
			  'Facebook' => $user->link,
			),
			'credentials' => array(
				'uid' => $user->id,
				'provider' => $this->name,
				'token' => $token,
			),
		);
	}
}
