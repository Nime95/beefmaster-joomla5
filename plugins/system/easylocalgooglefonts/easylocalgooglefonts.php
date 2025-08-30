<?php
/*
 *      @package Joomla.Plugin
 *      @subpackage  System.Easylocalgooglefonts
 *      @author JoomBoost
 *      @copyright Copyright (C) 2019 JoomBoost. All rights reserved
 *      @license GNU/GPL v3 or later
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class plgSystemEasylocalgooglefonts extends JPlugin
{

    public $newCSS = [];
    public $newLinks = [];
    public $loadedFiles = null;

    public function onBeforeCompileHead()
    {
        $document = JFactory::getDocument();
        $app = JFactory::getApplication();

        // perform this operation only in frontend
        if ($app->isClient('administrator')) return;

        $this->loadedFiles = $document->_styleSheets;
        $loaded_files_keys = array_keys($this->loadedFiles);


        $this->updateAssets($loaded_files_keys,$this->loadedFiles);

        $document->_styleSheets = $this->loadedFiles;


        foreach ($this->newCSS as $css) {
            $document->addStyleSheet($css);
        }

    }


    public function updateAssets($assets,&$loaded_files = null){

        $newCSS = [];

        foreach ($assets as $script) {
            if (strpos($script, 'fonts.googleapis.com') !== false) {
                $files = array();
                $url = parse_url($script, PHP_URL_QUERY);
                parse_str($url, $params);
                if (isset($params['family'])) {
                    $md5cssURI = JURI::base() . 'media/plg_system_easylocalgooglefonts/fonts/' . md5($params['family']) . '.css';
                    $md5css = JPATH_ROOT . '/media/plg_system_easylocalgooglefonts/fonts/' . md5($params['family']) . '.css';
                    if (!file_exists($md5css)) {
                        $content = $this->httpGetContents($script);
                        $exp = explode("\n", $content);
                        foreach ($exp as $line) {
                            if (strpos($line, 'url(') !== false) {
                                $files[] = $this->getStringBetween($line, 'url(', ')');
                            }
                        }
                        foreach ($files as $file) {
                            $exp = explode("/", $file);
                            $rfile = $exp[count($exp) - 1];
                            if (!file_exists(JPATH_ROOT . "/media/plg_system_easylocalgooglefonts/fonts/" . $rfile)) {
                                JFile::write(JPATH_ROOT . "/media/plg_system_easylocalgooglefonts/fonts/" . $rfile, $this->httpGetContents($file));
                            }
                            $content = str_replace($file, JURI::base() . "media/plg_system_easylocalgooglefonts/fonts/{$rfile}", $content);
                        }
                        JFile::write($md5css, $content);

                    }

                    if(!is_null($loaded_files))
                        unset($this->loadedFiles[$script]);

                    $this->newCSS[] = $md5cssURI;
                    $this->newLinks[] = '<link href="'.$md5cssURI.'" rel="stylesheet" type="text/css"/>';

                }
            }
        }

        return true;

    }


    /*
     *
     */
    public function onAfterRender()
    {

        $document = Factory::getDocument();

        $mainframe = JFactory::getApplication();



        if (!$mainframe->isClient('administrator')) {

            $buffer = Factory::getApplication()->getBody();
            $regex = '#<link href=["|\'](https?:\/\/fonts.googleapis.com\/css\?family=.*)["|\'] rel=["|\']stylesheet["|\'] type=["|\']text\/css["|\'] \/>#m';

            $matches = [];

            $found = preg_match_all($regex,$buffer,$matches);

            if(!$found)
                return true;



            if(isset($matches[1]) && count($matches[1]) > 0){

                $this->updateAssets($matches[1]);


                $buffer = str_replace($matches[0],$this->newLinks,$buffer);


                JFactory::getApplication('site')->setBody($buffer);

            }

        }

        return true;
    }


    public function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /*
     * Get content from remote file
     */
    public function httpGetContents($url)
    {

        $appendProtocol = strpos($url, 'http') === false ? 'https:' : '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, empty(ini_get('open_basedir')));
        curl_setopt($ch, CURLOPT_URL, $appendProtocol . $url);
        curl_setopt($ch, CURLOPT_REFERER, $appendProtocol . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (FALSE === ($retval = curl_exec($ch))) {
            error_log(curl_error($ch));
            curl_close($ch);
        } else {
            curl_close($ch);
            return $retval;
        }
    }
}
