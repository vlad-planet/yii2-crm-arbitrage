<?php

namespace common\models;

class IspManager
{
	public $ip;
	public $login;
	public $pswd;
	
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
	
	
	/**
     * available from ISPmanager API
	 * @return mixed
     */
	public function available($ip,$login,$pswd)
	{
		$this->ip = $ip;
		$this->login = $login;
		$this->pswd = $pswd;
	}

	/**
     * Action execute by ISPmanager API
	 * @return mixed
     */
	public function execute($func,array $params)
	{
		$url	= "https://".$this->ip."/ispmgr";
		$data	= "?authinfo=".$this->login.":".$this->pswd."&out=xml";
		$func	= "&func=".$func;
		$attr = '';
		
		foreach($params as $val => $key){
			$attr .= '&'.$val.'='.$key;
		}
		
		$link = $url.$data.$func.$attr; //var_dump($link); exit;
		
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);
		
		$res = file_get_contents($link, false, stream_context_create($arrContextOptions));
		$xml = (array)simplexml_load_string($res);

		return $xml;
	}
	
	/**
	 * @return status and error log
     */
	public function status($ex,bool $view=false)
	{
		$res=[];
		$res["Status"] = "OK"; // *? var_dump($ex); exit;
		
		if($view == true && isset($ex["active"])){
			$res["Status"] = "OK";
		}
		if(isset($ex['ok'])){
			$res["Status"] = "OK";
		}
		if(isset($ex["error"])){
			$ex = (array)$ex["error"];
			$res["Status"] = "ISPmanager:\n ".$ex["msg"];
		}
		return $res;
	}
	
	/**
     * Action systemInfo by ISPmanager API
	 * @return mixed
     */
	public function systemInfo()
	{
		$func = 'sysinfo.disk';
		$params = array();
		
		$ex = $this->execute($func,$params);
		return $ex["elem"];
	}
	
	/**
     * Action licenseInfo by ISPmanager API
	 * @return mixed
     */
	public function licenseInfo()
	{
		$func = 'license.info';
		$params = array();
		
		$ex = $this->execute($func,$params);
		//$ex = $this->status($ex);
		return $ex;
	}
	
	/**
     * Action diskUsage by ISPmanager API
	 * @return mixed
     */
	public function diskUsage(){
		$func = 'diskusage';
		$params = array();
		$ex = $this->execute($func,$params);
		
		$res = $this->status($ex);

		if(isset($ex["elem"])){

			foreach($ex["elem"] as $elem){
				$arr = (array)$elem;
				if($arr['name'] ==  'www'){
					$fs = $arr['fullsize'];
				}
			}
			$fs = explode(' ',$fs);

			switch ($fs[1][0]) {
				case 'K':
					$fs[0] /= 1024;
					break;
				case 'G':
					$fs[0] *= 1024;
					break;
			}
			$res['fullsize'] = (int)$fs[0];
		}
		return $res;
	}
	
	/**
     * Action editUser by ISPmanager API
	 * @return mixed
     */
	public function editUser($user,bool $view=false)
	{
		$func = 'user.edit';
		$params = array(
			'sok' => 'ok',
			'name' => $user,
			'fullname' => $user,
			'passwd' => $this->pswd,
			'confirm' => $this->pswd,
			'limit_ssl' => 'on',
			//'limit_php_mode_lsapi' => 'on',
			'limit_php_mode_fcgi_apache' => 'on',
			'limit_php_cgi_version' => 'isp%2Dphp74',//'7.4.21'
		);
		
		if($view == true){
			$params = array(
				'elid' => 'admin',
			);
		}
		
		$ex = $this->execute($func,$params);
		$ex = $this->status($ex,$view);
		return $ex;
	}
	
	/**
     * Action deleteUser by ISPmanager API
	 * @return mixed
     */
	public function deleteUser($user)
	{
		$func = 'user.delete';
		$params = array(
			'elid' => $user,
		);
		
		$ex = $this->execute($func,$params);
		$ex = $this->status($ex);
		return $ex;
	}
	
	/**
     * Action deleteUser by ISPmanager API
	 * @return mixed
     */
	public function deleteWebDomain($domain)
	{
		$func = 'webdomain.delete';
		$params = array(
			'elid' => $domain,
			'remove_directory' => 'on',
			'confirm' => 'on',
		);
		
		$ex = $this->execute($func,$params);
		$ex = $this->status($ex);
		return $ex;
	}
	
	/**
     * Action deleteSslCert by ISPmanager API
	 * @return mixed
     */
	public function deleteSslCert($domain)
	{
		if($this->login == 'root'){
			$user = 'admin';
		}else{
			$user = $this->login;
		}
		
		$func = 'sslcert.delete';
		$params = array(
			'elid' =>  $user."%25%23%25".$domain.",%20".$user."%25%23%25".$domain."_le", 
			//'elid' =>  "admin%#%".$domain.", admin%#%".$domain."_le",
		);

		$ex = $this->execute($func,$params);
		$ex = $this->status($ex);
		return $ex;
	}

	/**
     * Action LetsEncrypt Generate by ISPmanager API
	 * @return mixed
     */
	public function addCertLetsEncrypt($domain)
	{
		$func = 'letsencrypt.generate';
		$params = array(
			'enable_cert' => 'on',
			//'dns_check' => 'on',
			'domain_name' => $domain,
			'crtname' => $domain.'_le',
			//'username' => 'admin',
			'domain' => $domain.'%20www.'.$domain,
			'name' => $domain.'_le',
			'email' => 'admin@'.$domain,
			'sok' => 'ok',
		);

		$ex = $this->execute($func,$params);
		$ex = $this->status($ex);
		return $ex;
	}

	/**
     * Action webDomainEdit by ISPmanager API
	 * @return mixed
     */
	public function addWebDomain($domain)
	{
		$func = 'webdomain.edit';
		$params = array(
			'sok' => 'yes',
			'elid' => '',
			'name' => $domain,
			'alias' => 'www.'.$domain,
			'home' => '/www/'.$domain,
			//'owner' => 'admin',
			//'ipaddrs' => $_SERVER['SERVER_ADDR'],
			'email' => 'admin@'.$domain,
			'charset' => 'UTF-8',
			'secure' => 'on',
			'hsts' => 'on',
			'redirect_http' => 'on',
			'php' => 'on',
			'ssl_port' => 443,
			//'ssl_cert' => 'selfsigned',
		);
		
		$ex = $this->execute($func,$params);
		$ex = $this->status($ex);
		return $ex;
	}
}