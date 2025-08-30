<?php
// No direct access to this file
defined('_JEXEC') or die;

/**
 * Script file of HelloWorld module
 */
class Tx_MorphInstallerScript
{
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
        $client = new JoomInsights\Client('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9-eyJ1c2VyX2lkIjoiNzU4Iiwic2x1ZyI6InRwbF90eF9tb3JwaCJ9', 'tpl_tx_morph', 'template');
        $client->insights()->send_tracking_data($type); // install, uninstall, update
    }

    
}
