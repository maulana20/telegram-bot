<?php

class TelegramBot
{
	protected $token;
	protected $url;
	
	public function __construct($url, $token)
	{
		$this->url = $url;
		$this->token = $token;
	}
	
	public function call($method, $data = NULL)
	{
		$data_get = $postdata = NULL;
		if (!empty($data)) {
			$params = array();
			foreach ($data as $k => $v) {
				$params[] = $k . '=' . ( (is_array($v)) ? json_encode($v) : $v );
			}
			$data_get = '?' . implode('&', $params);
		}
		$uri = $this->url . '/bot' . $this->token . '/' . $method . $data_get;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36");
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);  // <-- add this line
		curl_setopt($ch, CURLOPT_REFERER, $this->url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$result = curl_exec($ch);
		$res_json = json_decode($result, true);

		if (!$res_json['ok']) {
			throw new Exception($res_json['description'], $res_json['error_code']);
		}
		
		return $res_json['result'];
	}
	
	public function __call($name, $arguments)
	{
		return $this->call($name, $arguments ? $arguments[0] : null);
	}
}
