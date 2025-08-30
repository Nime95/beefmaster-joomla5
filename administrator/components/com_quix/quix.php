<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_quix'))
{
  throw new JAccessExceptionNotallowed(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

try
{
  $controller = BaseController::getInstance('Quix');
  $controller->execute(Factory::getApplication()->input->get('task'));
  $controller->redirect();
}
catch (Exception $e)
{
  JErrorPage::render($e);
}
