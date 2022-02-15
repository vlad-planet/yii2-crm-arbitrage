<?php
namespace common\models;
use Yii;

class Ftp
{
	public $ftp;

	public function connectServer($ip,$login,$pass)
	{
		list($ip, $port) = explode(':',$ip);
		$this->sftp = Yii::$app->sftp;

		//$ip = 'isp12.adminvps.ru';
		//$login = 'domain12';
		//$pass = 'IN:nx6S9b3Ik!2';

		$this->ftp = ftp_connect($ip);

		if(ftp_login($this->ftp, $login, $pass)){
			ftp_pasv($this->ftp, true);
			return true;
		}else{
			return false;
		}
	}
	
	public function curlExecute($url){
		
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			
			$ex = curl_exec($ch);
			return $ex;
	}
	
	public function transferFiles($domain,$file)
	{
		$executor = 'unzip.php';
		$clear = 'delete.php';
		$tmp =\Yii::getAlias('@upload').'/';
		
		$zip_up = ftp_put($this->ftp, '/www/'.$domain.'/'.$clear, $tmp.'system/'.$clear, FTP_ASCII);

		$url = 'http://'.$domain.'/'.$clear;		//clear domain folder
		$ex = $this->curlExecute($url);

		if($ex){
			$zip_up = ftp_put($this->ftp, '/www/'.$domain.'/'.$executor, $tmp.'system/'.$executor, FTP_ASCII);
			$file_up = ftp_put($this->ftp, '/www/'.$domain.'/'.$file, $tmp.$file, FTP_BINARY);

			if($file_up == true && $zip_up == true){

				$url = 'http://'.$domain.'/'.$executor.'?file='.$file;		//unzip in domain folder
				$ex = $this->curlExecute($url);

				if($ex){
					ftp_delete($this->ftp, '/www/'.$domain.'/'.$file);
					ftp_delete($this->ftp, '/www/'.$domain.'/'.$executor);
					unlink($tmp.$file);
					return true;
				}
			}
		}
		return false;
	}

	function dirSize($ftpStream, $dir) {
		$size = 0;
		$files = ftp_nlist($ftpStream, $dir);

		foreach ($files as $remoteFile) {
			if(preg_match('/.*\/\.\.$/', $remoteFile) || preg_match('/.*\/\.$/', $remoteFile)){
				continue;
			}
			$sizeTemp = ftp_size($ftpStream, $remoteFile);
			if ($sizeTemp > 0) {
				$size += $sizeTemp;
			}elseif($sizeTemp == -1){
				$size += $this->dirSize($ftpStream, $remoteFile);
			}
		}
		return $size;
	}

	public function dirData($domain=null)
	{
		$dir = '/www/'.$domain.'/';
		if(ftp_nlist($this->ftp,$dir)){
			$size =  $this->dirSize($this->ftp, $dir);

			$val['size']  = round(($size / 1024 / 1024), 2);

			if(ftp_nlist($this->ftp, $dir.'*.html')){
				$val['count'] = count(ftp_nlist($this->ftp, $dir.'*.html'));
			}
			
			ftp_close($this->ftp);
			return $val;
		}
		
		return false;
	}

}

