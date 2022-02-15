<?php

namespace common\models;

class NameCheap{

	protected static $_instance;
	private function __construct() {}
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self;  
		}
		return self::$_instance;
	}
	private function __clone(){}
	private function __wakeup(){}  

	public function executeRequest($func,array $params)
	{
		$conf = Registrator::findByName('NameCheap');
		
		//$conf = new Registrator();
		
		$attr = '';
		if($params){
			foreach($params as $val => $key){
				$attr .= '&'.$val.'='.$key;
			}
		}

		$url = $conf->api_url;
		$url.='?ApiUser='.$conf->user;
		$url.='&ApiKey='.$conf->api_key;
		$url.='&UserName='.$conf->user;
		$url.='&Command='.$func;
		$url.='&ClientIp='.$conf->ip;
		$url.= $attr;
		
		$res = file_get_contents($url);
		$xml = (array)simplexml_load_string($res);

		return $xml;
	}
	
	public function complete($xml)
	{
		$res = [];
		$res["Status"] = $xml["@attributes"]["Status"];

		if(!empty($xml["Errors"])){
			$err = (array)$xml["Errors"];
			$res["Status"] .= "\n NameCheap:\n ".$err["Error"];
		}
		return $res;
	}

	public function getDomain($domain)
	{
		$func = 'namecheap.domains.check';
		$parrams = array(
			'DomainList' => $domain,
		);

		$ex = $this->executeRequest($func, $parrams);
		$ex = $this->complete($ex);
		return $ex;
	}

	public function updateDNSDomain($domain,$ns=null)
	{
		$domain = explode('.',$domain);
		$val = array_slice($domain, -2);

		if($ns == null){
			$func = 'namecheap.domains.dns.setDefault';
			$parrams = array(
				'SLD' => $val[0],
				'TLD' => $val[1],
			);
		}else{
			$func = 'namecheap.domains.dns.setCustom';

			$ns = str_replace(" ", ",", $ns);
			$parrams = array(
				'SLD' => $val[0],
				'TLD' => $val[1],
				'NameServers' => $ns 
			);
		}
		$ex = $this->executeRequest($func, $parrams);
		$ex = $this->complete($ex);
		return $ex;
	}
	
	public function addRecord($domain,$ip)
	{
		$func='namecheap.domains.dns.setHosts';
		
		$domain = explode('.',$domain);
		$val = array_slice($domain, -2); 
		
		$parrams = array(
			'SLD' => $val[0],
			'TLD' => $val[1],
			'HostName1' => '@',
			'RecordType1' => 'A',
			'Address1' => $ip,
			'TTL1' => 60000,
			'HostName2' => 'www',
			'RecordType2' => 'A',
			'Address2' => $ip,
			'TTL2' => 60000,
			'HostName3' => '*',
			'RecordType3' => 'A',
			'Address3' => $ip,
			'TTL3' => 60000,
		);
		
		$ex = $this->executeRequest($func, $parrams);
		$ex = $this->complete($ex);
		return $ex;
	}
	
	public function requestStatus($domain)
	{
		$func = 'namecheap.domains.getinfo';
		
		$parrams = array(
			'DomainName' => $domain,
		);
		
		$ex = $this->executeRequest($func, $parrams);
		$ex = strtotime($ex["CommandResponse"]->DomainGetInfoResult->DomainDetails->ExpiredDate);
		return $ex;
	}
	
	public function logOut()
	{
		return true;
	}
	
}