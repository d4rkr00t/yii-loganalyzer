<?php
/**
 * CFileLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CFileLogRoute records log messages in files.
 *
 * The log files are stored under {@link setLogPath logPath} and the file name
 * is specified by {@link setLogFile logFile}. If the size of the log file is
 * greater than {@link setMaxFileSize maxFileSize} (in kilo-bytes), a rotation
 * is performed, which renames the current log file by suffixing the file name
 * with '.1'. All existing log files are moved backwards one place, i.e., '.2'
 * to '.3', '.1' to '.2'. The property {@link setMaxLogFiles maxLogFiles}
 * specifies how many files to be kept.
 *
 * @property string $logPath Directory storing log files. Defaults to application runtime path.
 * @property string $logFile Log file name. Defaults to 'application.log'.
 * @property integer $maxFileSize Maximum log file size in kilo-bytes (KB). Defaults to 1024 (1MB).
 * @property integer $maxLogFiles Number of files used for rotation. Defaults to 5.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CFileLogRoute.php 3426 2011-10-25 00:01:09Z alexander.makarow $
 * @package system.logging
 * @since 1.0
 */
class LALogRoute extends CFileLogRoute
{
    /**
     * Saves log messages in files.
     * @param array $logs list of log messages
     */
    protected function processLogs($logs)
    {
        $logFile=$this->getLogPath().DIRECTORY_SEPARATOR.$this->getLogFile();
        if(@filesize($logFile)>$this->getMaxFileSize()*1024)
            $this->rotateFiles();
        $fp=@fopen($logFile,'a');
        // @flock($fp,LOCK_EX);

        foreach($logs as $log) {
            @fwrite($fp,$this->formatLogMessage($log[0],$log[1],$log[2],$log[3]));
            // @fwrite($fp,"\n123 123 12kj3k12j3 k1l23jk12j31l2;k3j 1k2l;j3 kl12;j3 12kl3j \n---");
        }

        // @flock($fp,LOCK_UN);
        @fclose($fp);
    }

    /**
     * Formats a log message given different fields.
     * @param string $message message content
     * @param integer $level message level
     * @param string $category message category
     * @param integer $time timestamp
     * @return string formatted message
     */
    protected function formatLogMessage($message,$level,$category,$time)
    {
        $ip = @$this->get_ip();
        if ($ip) {
            return @date('Y/m/d H:i:s',$time)." [ip:".$ip."] [$level] [$category] $message\n";
        } else {
            return @date('Y/m/d H:i:s',$time)." [$level] [$category] $message\n";
        }
        
    }

    /**
     * функция определяет ip адрес по глобальному массиву $_SERVER
     * ip адреса проверяются начиная с приоритетного, для определения возможного использования прокси
     * @return ip-адрес
     */
    protected function get_ip()
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipa[] = trim(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipa[] = $_SERVER['HTTP_CLIENT_IP'];       
        
        if (isset($_SERVER['REMOTE_ADDR']))
            $ipa[] = $_SERVER['REMOTE_ADDR'];
        
        if (isset($_SERVER['HTTP_X_REAL_IP']))
            $ipa[] = $_SERVER['HTTP_X_REAL_IP'];
        
        // проверяем ip-адреса на валидность начиная с приоритетного.
        foreach($ipa as $ips)
        {
            //  если ip валидный обрываем цикл, назначаем ip адрес и возвращаем его
            if($this->is_valid_ip($ips))
            {                    
                $ip = $ips;
                break;
            }
        }
        return $ip;
    }
    
    /**
     * функция для проверки валидности ip адреса
     * @param ip адрес в формате 1.2.3.4
     * @return bolean : true - если ip валидный, иначе false
     */
    protected function is_valid_ip($ip=null)
    {
        if(preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#", $ip))
            return true; // если ip-адрес попадает под регулярное выражение, возвращаем true
        
        return false; // иначе возвращаем false
    }

}
