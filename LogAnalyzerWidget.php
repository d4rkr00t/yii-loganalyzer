<?php
/**
 * LogAnalyzerWidget class file.
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

class LogAnalyzerWidget extends CWidget {
    public $filters = array();

    public $log_file_path;

    public $title = 'Log Analyzer';

    private $last_status;

    public function init()
    {
        parent::init();

        if (!$this->log_file_path) {
            $this->log_file_path = Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'application.log';
        }
    }

    public function run()
    {
        if (isset($_GET['log'])) {
            file_put_contents($this->log_file_path, '');
            Yii::app()->controller->redirect($this->getUrl(false));
        }
        
        /**
         * Загружаем лог
         */
        $log = file_get_contents($this->log_file_path);

        /**
         * Разбиваем лог на сообщения
         */
        $log = explode('---', $log);

        $pop = array_pop($log);

        $log = array_reverse($log);

        $this->registerAssets();

        $this->render('index', array(
            'log' => $log
        ));
    }

    /**
     * Register CSS and JS files.
     */
    protected function registerAssets() {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');

        $assets_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
        $url = Yii::app()->assetManager->publish($assets_path, false, -1, YII_DEBUG);
        
        if (defined('DEBUG')) {
            $cs->registerCssFile($url.'/log.css');
        } else {
            $cs->registerCssFile($url.'/log.min.css');
        }
    }


    public function filterLog($text)
    {
        foreach ($this->filters as $f) {
            if (preg_match('/'.$f.'/',$text)) {
                return false;
            }
        }

        return true;
    }

    public function showDate($text)
    {
        return date('H:i d.m.Y', strtotime(mb_substr($text, 0, 20,'utf8')));
    }

    public function showError($text)
    {
        $text = mb_substr($text, 20, mb_strlen($text,'utf8'),'utf8');

        $text = explode('Stack trace:', $text);
        $text = $text[0];

        if ($this->last_status != "") {
            $text = str_replace($this->last_status." ", "", $text);
        }

        return $text;
    }

    public function showStack($text)
    {
        $text = explode('Stack trace:', $text);
        return @$text[1];
    }

    public function showStatus($text)
    {
        if (preg_match('[error]',$text)) {
            $this->last_status = '[error]';
            return array('status'=>'error', 'class'=>'label-important');
        } elseif (preg_match('[warning]',$text)) {
            $this->last_status = '[warning]';
            return array('status'=>'warning', 'class'=>'label-warning');
        } elseif (preg_match('[info]',$text)) {
            $this->last_status = '[info]';
            return array('status'=>'info', 'class'=>'label-info');
        }else {
            return array('status'=>'undefined', 'class'=>'');
        }
    }

    public function getUrl($clear = true)
    {
        $url = '/';

        if (Yii::app()->controller->module) {
            $url .= Yii::app()->controller->module->getId().'/';
        }

        $url .= Yii::app()->controller->getId().'/'.Yii::app()->controller->getAction()->getId();

        if ($clear) {
            $url .= '/log/clear';
        }

        return Yii::app()->controller->createUrl($url);
    }
}