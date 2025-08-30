<?php
/**
* @package ThemeXpert
* @author ThemeXpert http://www.themexpert.com
* @copyright Copyright (c) 2010 - 2016 ThemeXpert
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
* @credit: Helix Framework
*/

//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField
{
    protected	$type = 'Asset';

    protected function getInput() {

        $path = str_replace (JPATH_ROOT, '', dirname(__DIR__));
        $path = str_replace ('\\', '/', substr($path, 1));

        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root(true) .'/'. $path.'/assets/css/admin.css');
        $doc->addScript(JUri::root(true) .'/'. $path.'/assets/js/webfont.js');

    }
}