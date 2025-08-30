<?php
/**
 * @package   Gantry 5 Theme
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2017 RocketTheme, LLC
 * @license   GNU/GPLv2 and later
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

class Tx_AutomexInstallerScript
{
    public $requiredGantryVersion = '5.4.0';

    /**
     * @param string $type
     * @param object $parent
     * @return bool
     * @throws Exception
     */
    public function preflight($type, $parent)
    {
        if ($type === 'uninstall') {
            return true;
        }

        $manifest = $parent->getManifest();
        $name = JText::_($manifest->name);

        // Prevent installation if Gantry 5 isn't enabled or is too old for this template.
        try {
            if (!class_exists('Gantry5\Loader')) {
                throw new RuntimeException(sprintf('Please install Gantry 5 Framework before installing %s template!', $name));
            }

            Gantry5\Loader::setup();

            $gantry = Gantry\Framework\Gantry::instance();

            if (!method_exists($gantry, 'isCompatible') || !$gantry->isCompatible($this->requiredGantryVersion)) {
                throw new \RuntimeException(sprintf('Please upgrade Gantry 5 Framework to v%s (or later) before installing %s template!', strtoupper($this->requiredGantryVersion), $name));
            }

        } catch (Exception $e) {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::sprintf($e->getMessage()), 'error');

            return false;
        }

        return true;
    }

    /**
     * @param string $type
     * @param object $parent
     * @throws Exception
     */
    public function postflight($type, $parent)
    {
        $installer = new Gantry\Framework\ThemeInstaller($parent);
        $installer->initialize();

        // Install sample data on first install.
        if (in_array($type, array('install', 'discover_install'))) {
            try {
                $installer->installDefaults();

                echo $installer->render('install.html.twig');

            } catch (Exception $e) {
                $app = JFactory::getApplication();
                $app->enqueueMessage(JText::sprintf($e->getMessage()), 'error');
            }
        } else {
            echo $installer->render('update.html.twig');
        }

        $installer->finalize();
    }

    /**
     * Called by TemplateInstaller to customize post-installation.
     *
     * @param \Gantry\Framework\ThemeInstaller $installer
     */
    public function installDefaults(Gantry\Framework\ThemeInstaller $installer)
    {
        // Create default outlines etc.
        $installer->createDefaults();
    }

    /**
     * Called by TemplateInstaller to customize sample data creation.
     *
     * @param \Gantry\Framework\ThemeInstaller $installer
     */
    public function installSampleData(Gantry\Framework\ThemeInstaller $installer)
    {
        // Create sample data.
        $installer->createSampleData();
    }

    /* For JoomInsights */

    /**
     * Method to install the extension
     * $parent is the class calling this method
     *
     * @return void
     */
    function install($parent)
    {
        $this->logJoomInsights('install');
    }

    /**
     * Method to uninstall the extension
     * $parent is the class calling this method
     *
     * @return void
     */
    function uninstall($parent)
    {
        $this->logJoomInsights('uninstall');
    }

    /**
     * Method to update the extension
     * $parent is the class calling this method
     *
     * @return void
     */
    function update($parent)
    {
        $this->logJoomInsights('update');
    }

    /**
     * Lets call the joominsights
     *
     * @return void
     */
    function logJoomInsights($type)
    {
        if( !JFile::exists(__DIR__ . '/lib/joomInsights/src/Client.php') ) return;
        if ( ! class_exists( 'JoomInsights\Client' ) ) require_once __DIR__ . '/lib/joomInsights/src/Client.php';
        // init the Client
        $client = new JoomInsights\Client('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9-eyJ1c2VyX2lkIjoiNzU4Iiwic2x1ZyI6InR4X2F1dG9tZXgifQ', 'tx_automex', 'template');
        $client->insights()->send_tracking_data('install'); // install, uninstall, update
    }    
}
