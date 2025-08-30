<?php
/**
 * @package     Jvextensions.Plugin
 * @subpackage  Captcha
 *
 * @copyright   (C) 2022 JV-Extensions.com. <https://www.jv-extensions.com>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerAdapter;

class plgcaptchajvmathInstallerScript
{
    public function postflight($route, InstallerAdapter $adapter)
    {
        if ($route == 'update') {
            try {
                $db = Factory::getDbo();
                $db->setQuery("delete from #__update_sites where location = 'https://www.jv-extensions.com/updates/jvmathj4_updates.xml' or location = 'https://jvextensions-xml-files.s3.eu-west-1.amazonaws.com/jvmathcaptcha-j4/jvmathj4_updates.xml'");
                $db->execute();
            }
            catch (Exception $e) {
            }
        }

        return true;
    }
}