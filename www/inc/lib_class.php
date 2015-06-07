<?PHP
if(!defined('IN_PT')) exit('Access Denied');
//分页的类
class timer
	{
	    var $StartTime = 0;
	    var $StopTime = 0;
	    var $TimeSpent = 0;

		function start(){
			$this->StartTime = microtime();
		}
		function stop(){
			$this->StopTime = microtime();
		}
		function spent(){
			if ($this->TimeSpent) {
				return $this->TimeSpent;
			} else {
				$StartMicro = (float)substr($this->StartTime,0,10);
				$StartSecond = (int)substr($this->StartTime,11,10);
				$StopMicro = (float)substr($this->StopTime,0,10);
				$StopSecond= (int)substr($this->StopTime,11,10);
				$start = doubleval($StartMicro)+$StartSecond;
				$stop=doubleval($StopMicro)+$StopSecond;
				$this->TimeSpent=$stop-$start;
				return substr($this->TimeSpent,0,8)."秒";
			}
		}
	}

?>