<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jmedia
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;


$input = Factory::getApplication()->input;
$user = Factory::getUser();
$asset = $input->get('asset');
$author = $input->get('author');

// Access check.
if (!$user->authorise('core.manage', 'com_jmedia') && (!$asset || (!$user->authorise('core.edit', $asset)
    && !$user->authorise('core.create', $asset)
    && count($user->getAuthorisedCategories($asset, 'core.create')) == 0)
    && !($user->id == $author && $user->authorise('core.edit.own', $asset)))) {

    if($input->get('format') === 'json'){
        echo new \Joomla\CMS\Response\JsonResponse(new \Joomla\CMS\Access\Exception\NotAllowed(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403));
        exit();
    }else{
        throw new \Joomla\CMS\Access\Exception\NotAllowed(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
    }
}

$params = ComponentHelper::getParams('com_jmedia');

// Load the helper class
JLoader::register('JMediaHelper', JPATH_ADMINISTRATOR . '/components/com_jmedia/helpers/jmedia.php');

// Set the path definitions
$popup_upload = $input->get('pop_up', null);
$path = 'file_path';
$view = $input->get('view');

if (substr(strtolower($view), 0, 6) == 'images' || $popup_upload == 1) {
    $path = 'image_path';
}

// Define consts
define('COM_JMEDIA_PREFIX', $params->get($path, 'images'));
define('COM_JMEDIA_BASE', JPATH_ROOT . '/' . $params->get($path, 'images'));
define('COM_JMEDIA_BASEURL', Uri::root() . $params->get($path, 'images'));
define('COM_JMEDIA_AUTHOR', Factory::getUser()->id);

// Process the content plugins.
PluginHelper::importPlugin('system');
$results = Factory::getApplication()->triggerEvent('onJMediaLicenseValidation', ['com_jmedia.license']);
if (!in_array(true, $results)) {
    define('JMDEDIA_LICENSE', 'FREE');
}

$controller = BaseController::getInstance('JMedia', ['base_path' => JPATH_COMPONENT_ADMINISTRATOR]);
$controller->execute($input->get('task'));
$controller->redirect();
