<?php 

namespace common\models;

class CloudApi{

	public 	$email;
	public 	$api_key;
	public 	$acc_id;
	public 	$url = 'https://api.cloudflare.com/client/v4/';
	
	public function __construct($email,$api_key,$acc_id){
		$this->email = $email;
		$this->api_key = $api_key;
		$this->acc_id = $acc_id;
	}

	public function getResponse($fnc,$data)
    {
		foreach($data as $data => $key){
			$fnc .= '&'.$data.'='.$key;
		}

        $ch = curl_init($this->url.$fnc);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
			'X-Auth-Email: '.$this->email,	
			'X-Auth-Key: '.$this->api_key,
			'Content-Type: application/json',                                                  
			)                                                                     
		);
		$res = json_decode(curl_exec($ch),true);
		curl_close($ch);
		
		$res["Status"] = 'OK';
		if($res["success"] == false){
			$res["Status"] = 'CF: '. $res["errors"][0]["message"];
		}
		return $res;
    }

	public function postResponse ($fnc, $data, $meth = "POST")
    {
        $ch = curl_init($this->url.$fnc);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $meth); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
			'X-Auth-Email: '.$this->email,	
			'X-Auth-Key: '.$this->api_key,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data)		
			)                                                                    
		); 
		$res = json_decode(curl_exec($ch),true);
		curl_close($ch);
		
		$res["Status"] = 'OK';
		if($res["success"] == false){
			$res["Status"] = 'CF: '. $res["errors"][0]["message"];
		}
		return $res;
    }
	
	public function userAccount()
    {
		$fnc = 'memberships?';
		$data = array(
			'status' => 'accepted',
			'page' => 1,
			'per_page' => 1,
			'order' => 'status',
			'direction' => 'desc'
		);
		$res = $this->getResponse($fnc,$data);
		return $res;
	}
	
	public function createDomain($domain,$acc_id)
	{
		$fnc = 'zones';
		$data = array(
			'name' => $domain,
			'account' => array('id'=>$acc_id),
			'jump_start' => true,
			'type' => 'full',
		);
		$data = json_encode($data);	
		$res = $this->postResponse($fnc, $data);
		return $res;
	}
	
	
	public function deleteDomain($domain)
	{
		$fnc = 'zones?';
		$data = array(
			'name' => 'p-stroycz.com',
			'account.id' => $this->acc_id,
		);
		$res = $this->getResponse($fnc, $data);

		$fnc = 'zones/'.$res["result"][0]["id"];
		$data = array(); $data = json_encode($data);
		$res = $this->postResponse($fnc, $data, 'DELETE');

		return $res;
	}
	
	
	public function CompatibilityIPv6($zone_id)
	{
			$fnc = 'zones/'.$zone_id.'/settings/ipv6';
			$data = array(
				'value' => 'off',
			);
			$data = json_encode($data);	
			$res = $this->postResponse($fnc, $data, 'PATCH');
			return $res;
	}
	
	public function dnsRecords($zone_id,$ip)
	{
		$fnc = 'zones/'.$zone_id.'/dns_records';
		$data = array(
			'type' => 'A',
			'name' => '@',
			'content' => $ip,
			'ttl' => 1,
			'priority' => 10,
			'proxied' => true
		);
		$data = json_encode($data);	
		$res = $this->postResponse($fnc, $data);
		return $res;
	}
}