<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Exception\ExceptionHandler;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

QuixFrontendHelper::showPHPVersionWarning();

// Get App instance
try
{
    $app   = Factory::getApplication();
    $input = $app->input;

    QuixFrontendHelper::checkComponentAuth($app, $input);

    $controller = BaseController::getInstance('Quix');
    $controller->execute($app->input->get('task'));
    $controller->redirect();
}
catch (Exception $e)
{
    ExceptionHandler::render($e);
}
