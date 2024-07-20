<?php

namespace Navi;

class Geo
{

	protected $data = [];

	public static function ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $ip;
	}

	public static function data($ip = NULL){
		$ip = ($ip == NULL) ? self::ip() : $ip;
		$url = 'http://api.sypexgeo.net/HLzco/json/' . $ip;
		$data = json_decode(file_get_contents($url));
		$result['city'] = (isset($data->city->name_en) && !empty($data->city->name_en)) ? $data->city->name_en : NULL;
		$result['country'] = (isset($data->country->iso) && !empty($data->country->iso)) ? $data->country->iso : NULL;
		return $result;
	}
}
?>