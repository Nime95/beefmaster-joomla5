<?php
/**
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org
 *------------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * T3 Blank Helper class
 *
 * @package		T3 Blank
 */


use Joomla\CMS\Event\AbstractEvent;

class Tx_MorphHook extends AbstractEvent
{

	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onT3Init() // no params
	{
        $app = JFactory::getApplication();
        if($app->isAdmin()){
            $layout = $app->input->get('layout');
            if($layout == 'edit'){
                jimport('joomla.form.helper');
                JForm::addFieldPath(dirname(__FILE__) . '/admin/fields');
            }
        }
	}

	public function onT3TplInit($t3app)
	{

	}

	public function onT3LoadLayout(&$path, $layout)
	{
		//T3::getApp()->addBodyClass('loadlayout');
	}

	public function onT3Spotlight(&$info, $name, $position)
	{

	}

	public function onT3Megamenu(&$menutype, &$config, &$levels)
	{

	}

	public function onT3BodyClass(&$class)
	{
		//$class[] = 'onbodyclass';
	}

	public function onT3BeforeCompileHead() // no params
	{

	}

	public function onT3BeforeRender() // no params
	{

	}

	public function onT3AfterRender() // no params
	{

	}

}

if(!function_exists('T3MenuMegamenuTpl_item_component')){
    function T3MenuMegamenuTpl_item_component($vars){
        $item     = $vars['item'];
        $class    = $vars['class'];
        $title    = $vars['title'];
        $dropdown = $vars['dropdown'];
        $caret    = $vars['caret'];
        $linktype = $vars['linktype'];
        $icon     = $vars['icon'];
        $caption  = $vars['caption'];
        // Note. It is important to remove spaces between elements.

        switch ($item->browserNav) :
            default:
            case 0:
                $link = "<a itemprop='url' class=\"$class\" href=\"{$item->flink}\" $title $dropdown>
                            $icon$linktype$caret$caption
                        </a>";
                break;
            case 1:
                // _blank
                $link = "<a itemprop='url' class=\"$class\" href=\"{$item->flink}\" target=\"_blank\" $title $dropdown>
                            $icon$linktype$caret$caption
                        </a>";
                break;
            case 2:
                // window.open
                $link = "<a itemprop='url' class=\"$class\" href=\"{$item->flink}\"" . (!$vars['menu']->editmode ? " onclick=\"window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;\"" : "") . " $title $dropdown>
                            $icon$linktype$caret$caption
                        </a>";
                break;
        endswitch;

        return $link;
    }
}

?>
