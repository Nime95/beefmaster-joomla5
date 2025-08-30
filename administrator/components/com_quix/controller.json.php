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

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;

/**
 * Class QuixController
 *
 * @since  1.0.0
 */
class QuixController extends BaseController
{
    /**
     * Set session from ajax request
     *
     * @return bool
     * @throws \Exception
     * @since 3.0.0
     */
    public function setSession(): bool
    {
        (JSession::checkToken('get') or JSession::checkToken()) or jexit(\Joomla\CMS\Language\Text::_('JINVALID_TOKEN'));
        $input   = Factory::getApplication()->input;
        $session = Factory::getSession();

        $key   = $input->get('key', false, 'string');
        $value = $input->get('value', false, 'string');
        if ($key && $value) {
            $session->set($key, $value);
            echo new JResponseJson(sprintf('"key": "%s", "value": "%s"; session has been updated.', $key, $value));

            return true;
        }

        echo new JResponseJson(new Exception(sprintf('Key or Value not found!')));

        return true;
    }

    /**
     * Set session from ajax request
     *
     * @return bool
     * @throws \Exception
     * @since 3.0.0
     */
    public function setCookie(): bool
    {
        (JSession::checkToken('get') or JSession::checkToken()) or jexit(\Joomla\CMS\Language\Text::_('JINVALID_TOKEN'));
        $input = Factory::getApplication()->input;

        $key   = $input->get('key', false, 'string');
        $value = $input->get('value', false, 'string');

        if ($key && $value) {
            $input->cookie->set($key, $value);
            echo new JResponseJson(sprintf('"key": "%s", "value": "%s"; Cookie has been updated.', $key, $value));

            return true;
        }

        echo new JResponseJson(new Exception(sprintf('Key or Value not found!')));

        return true;
    }

    /**
     * Update Component params
     *
     * @return bool
     * @throws \Exception
     * @since 3.0.0
     */
    public function setComponentParams(): bool
    {
        (JSession::checkToken('get') or JSession::checkToken()) or jexit(\Joomla\CMS\Language\Text::_('JINVALID_TOKEN'));
        $input = Factory::getApplication()->input;

        $key   = $input->get('key', false, 'string');
        $value = $input->get('value', false);

        if ($key && $value) {
            try {
                $result = QuixHelper::setComponentParams($key, $value);
                if($result){
                    echo new JResponseJson(sprintf('"key": "%s", "value": "%s"; Params has been updated.', $key, $value));
                    return true;
                } else {
                    echo new JResponseJson($result);
                    return false;
                }
            } catch (RuntimeException $e) {
                // $this->setError($e->getMessage());
                echo new JResponseJson($e);
                return true;
            }
        }

        echo new JResponseJson(new Exception('Key or Value not found!'));

        return true;
    }
}
