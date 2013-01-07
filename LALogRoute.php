<?php
/**
 * LALogRoute class file.
 *
 * Forked from:
 *    https://github.com/d4rkr00t/yii-loganalyzer
 *    Stanislav Sysoev <d4rkr00t@mail.ru>
 * 
 * @author Tonin R. Bolzan <admin@tonybolzan.com>
 * @copyright 2012, Odig Marketing Digital <odig.net>
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version 0.1
 */
class LALogRoute extends CFileLogRoute
{
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
        $message .= '.-==-.';
        
        $ip = @$this->get_ip();
        if ($ip) {
            return @date('Y/m/d H:i:s',$time)." [ip:".$ip."] [$level] [$category] $message\n";
        } else {
            parent::formatLogMessage($message, $level, $category, $time);
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
