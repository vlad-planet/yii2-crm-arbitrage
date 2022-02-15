<?php
namespace common\models;
use Yii;

class SshTunnel
{
	public $sftp;
	
	public function connectServer($ip,$login,$pass)
	{
		list($ip, $port) = explode(':',$ip);
		$this->sftp = Yii::$app->sftp;
		
		if($this->sftp->connect($ip,$login,$pass)){
			return true;
		}
		return false;
	}

    public function transferFiles($domain,$file) // send a file
    {
		$tmp = $_SERVER['DOCUMENT_ROOT'].'/backend/web/upload/';
		$folder = '/var/www/admin/data/www/';

		$this->sftp->exec("rm -rf ". $folder.$domain);
		$this->sftp->put($folder.$file, $tmp.$file,1);
		
		if($this->sftp->exec("unzip ".$folder.$file." -d ".$folder.$domain))
		{
			$this->sftp->exec("rm -rf ".  $folder.$file);
			unlink(\Yii::getAlias('@upload').'/'.$file);
			return true;
		}
		return false;
	}

	public function dirData($domain)
	{
		$folder = '/var/www/admin/data/www/'.$domain;
		
		if($this->sftp->stat($folder)){
			
			$sf = $this->sftp->exec("du -sh " . $folder);
			$sf = preg_split("/	/", $sf);
			$cf = $this->sftp->exec("find ".$folder." -type f  -iname '*.html' |wc -l");

			$this->sftp->disconnect();
			
			if($sf && $cf){
				$m = substr($sf[0], -1);
				$sf = $sf[0];

				switch ($m) {
					case 'K':
						$sf /= 1024;
						break;
					case 'G':
						$sf *= 1024;
						break;
				}
				
				$val['size'] = round($sf,1);
				$val['count'] = (int)$cf;

				return $val;
			}
		}
		
		return false;
	}
}