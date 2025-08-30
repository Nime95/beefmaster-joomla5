<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * pages Component Message Model
 *
 * @since  1.6
 */
class QuixControllerConfig extends BaseController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'pages';
		parent::__construct();
	}

	/**
	 * Method to save a record.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(\Joomla\CMS\Language\Text::_('JINVALID_TOKEN'));

		$app   = Factory::getApplication();
		$model = $this->getModel('Config', 'QuixModel');
		$data  = $this->input->post->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		$data = $model->validate($form, $data);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation pages.
			$errors = $model->getErrors();

			// Push up to three validation pages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Redirect back to the main list.
			$this->setRedirect(\Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=pages', false));

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Redirect back to the main list.
			$this->setMessage(\Joomla\CMS\Language\Text::sprintf('JERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(\Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=pages', false));

			return false;
		}

		// Redirect to the list screen.
		$this->setMessage(\Joomla\CMS\Language\Text::_('COM_QUIX_CONFIG_SAVED'));
		$this->setRedirect(\Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=pages', false));

		return true;
	}
}
