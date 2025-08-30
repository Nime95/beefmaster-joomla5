<?php
/**
 * @package     Quix
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @since       1.0.0
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;

class QuixSystemHelperLogin
{
    public $db;

    public function doLoginCheck()
    {
        $app = Factory::getApplication();

        // Check for a cookie if user is not logged in (quest cookie)
        if (Factory::getUser()->get('guest')) {
            // prepare cookie name
            $cookie_name = md5(JApplicationHelper::getHash('administrator'));
            if ($_COOKIE[$cookie_name] !== '') {
                $sessionId = $_COOKIE[$cookie_name];
                // find back-end session
                $this->db = Factory::getDbo();
                $query = $this->db->getQuery(true)
                ->select($this->db->quoteName(
                    ['session_id', 'client_id', 'guest', 'time', 'data', 'userid', 'username']
                ))
                ->from($this->db->quoteName('#__session'))
                ->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($sessionId))
                ->order('client_id ASC');
                $this->db->setQuery($query);
                $adminSession = $this->db->loadObject();
                if (empty($adminSession) || $adminSession->guest) {
                    return false;
                }

                // user is already logged to back-end
                $session = Factory::getSession();
                // Update the user related fields for the Joomla sessions table.
                $query = $this->db->getQuery(true)
                          ->update($this->db->quoteName('#__session'))
                          ->set($this->db->quoteName('client_id') . ' = ' . '0')
                          ->set($this->db->quoteName('guest') . ' = ' . '0')
                          ->set($this->db->quoteName('data') . ' = ' . $this->db->quote($adminSession->data))
                          ->set($this->db->quoteName('username') . ' = ' . $this->db->quote($adminSession->username))
                          ->set($this->db->quoteName('userid') . ' = ' . (int) $adminSession->userid)
                          ->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($session->getId()));
                $res = $this->db->setQuery($query)->execute();

                if ($res) {
                    $app = Factory::getApplication();
                    $userId = $adminSession->userid;
                    // new
                    $instance = JUser::getInstance($userId);
                    // If _getUser returned an error, then pass it back.
                    if ($instance instanceof Exception) {
                        return false;
                    }

                    // If the user is blocked, redirect with an error
                    if ($instance->block === '1') {
                        $app->enqueueMessage(\Joomla\CMS\Language\Text::_('JERROR_NOLOGIN_BLOCKED'), 'warning');
                        return false;
                    }

                    // Check the user can login.
                    $result = $instance->authorise('core.manage');
                    if (!$result) {
                        $app->enqueueMessage(\Joomla\CMS\Language\Text::_('JERROR_LOGIN_DENIED'), 'warning');
                        return false;
                    }

                    // Mark the user as logged in
                    $instance->guest = 0;

                    $session = Factory::getSession();

                    // Grab the current session ID
                    $oldSessionId = $session->getId();

                    // Fork the session
                    $session->fork();

                    $session->set('user', $instance);

                    // Ensure the new session's metadata is written to the database
                    $app->checkSession();

                    // Purge the old session
                    $query = $this->db->getQuery(true)
                                  ->delete('#__session')
                                  ->where($this->db->quoteName('session_id') . ' = ' . $this->db->quote($oldSessionId));

                    $this->db->setQuery($query)->execute();

                    // Hit the user last visit field
                    $instance->setLastVisit();

                    // Add "user state" cookie used for reverse caching proxies like Varnish, Nginx etc.
                    $app->input->cookie->set(
                        'joomla_user_state',
                        'logged_in',
                        0,
                        $app->get('cookie_path', '/'),
                        $app->get('cookie_domain', ''),
                        $app->isHttpsForced(),
                        true
                    );

                    $_SESSION['__default']['user'] = $instance;
                } else {
                    $app->enqueueMessage(\Joomla\CMS\Language\Text::_('Sorry! can\t authorize user.'), 'notice');
                }
            }
        }
    }
}
