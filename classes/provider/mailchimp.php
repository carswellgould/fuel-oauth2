<?php

namespace OAuth2;

class Provider_Mailchimp extends Provider {  
	
	public $name = 'mailchimp';
	
	public $method = 'POST';

	public function url_authorize()
	{
		return 'https://login.mailchimp.com/oauth2/authorize';
	}

	public function url_access_token()
	{
		return 'https://login.mailchimp.com/oauth2/token';
	}

	public function get_user_info($token)
	{		
		$curl = \Request::forge('https://login.mailchimp.com/oauth2/metadata', array(
			'driver' => 'curl',
			'method' => 'get'
		))->set_header('Authorization','OAuth '.$token);
		
		$response = $curl->execute();		
		
		if (intval($response->response()->status / 100) != 2) 
		{
			throw new \Fuel_Exception('There was a problem retrieving the MailChimp meta data');
		}
		
		// Create a response from the request
		return array(
			'urls' => array(
			  'endpoint' => $response->response()->body['api_endpoint'],
			),
			'credentials' => array(
				'uid' => md5($token),
				'provider' => $this->name,
				'token' => $token,
			),
		);
	}
}
