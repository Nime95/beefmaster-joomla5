<?php
/**
 * @package    Quix
 * @author     ThemeXpert http://www.themexpert.com
 * @copyright  Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @since      1.0.0
 */

defined('_JEXEC') or die;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Factory;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @package     Joomla.Administrator
 * @subpackage  com_quix
 * @since       3.4
 */
class pkg_QuixInstallerScript
{
    public $migration = false;

    /**
     * @param $type
     * @param $parent
     *
     * @return false
     * @throws \Exception
     * @since 3.0.0
     */
    public function preflight($type, $parent)
    {
        // if ($type === 'update') {
        //     // JError::raiseWarning(null, 'Beta Package can\'t be installed on existing site.');
        //     Factory::getApplication()->enqueueMessage('Beta Package can\'t be installed on existing site.', 'warning');
        //
        //     return false;
        // }

        return true;
    }

    /**
     * method to rename old tables to new name
     *
     * @return bool
     * @throws \Exception
     * @since 1.0.0
     */
    public function renameDB(): bool
    {
        $app    = Factory::getApplication();
        $prefix = $app->get('dbprefix');

        $db     = Factory::getDbo();
        $tables = Factory::getDbo()->getTableList();

        if (in_array($prefix.'quicx', $tables)) {
            $db->setQuery('RENAME TABLE #__quicx TO #__quix');
            $db->execute();
        }

        if (in_array($prefix.'quicx_collections', $tables)) {
            $db->setQuery('RENAME TABLE #__quicx_collections TO #__quix_collections');
            $db->execute();
        }

        if (in_array($prefix.'quicx_collection_map', $tables)) {
            $db->setQuery('RENAME TABLE #__quicx_collection_map TO #__quix_collection_map');
            $db->execute();
        }

        return true;
    }

    /*
     * get a variable from the manifest file (actually, from the manifest cache).
     */
    public function getParam($name, $options = 'com_quix')
    {
        $db = Factory::getDbo();
        $db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "'.$options.'"');
        $result = $db->loadResult();
        if (isset($result) && ! empty($result)) {
            $manifest = json_decode($result, true);

            return $manifest[$name];
        }

        return false;
    }

    /*
     * get a variable from the manifest file (actually, from the manifest cache).
     */
    public function uninstallOldExtensions()
    {
        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_SITE.'/adminstrator/components/com_installer/models', 'InstallerModel');
        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Manage', 'InstallerModel');
        $db    = Factory::getDbo();
        $db->setQuery("SELECT * FROM `#__extensions` WHERE `name` LIKE '%quicx%'");
        $results = $db->loadObjectList();
        if (isset($results) && ! empty($results)) {
            // print_r($results);die;
            $ids = [];
            foreach ($results as $key => $value) {
                $ids[] = $value->extension_id;
            }
            ArrayHelper::toInteger($ids, []);
            $model->remove($ids);
        }

        return true;
    }

    /**
     * update db structure
     *
     * @lang  mysqli
     * @since 3.0.0
     */
    public function updateDBfromOLD()
    {
        $app    = Factory::getApplication();
        $prefix = $app->get('dbprefix');

        $db     = Factory::getDbo();
        $tables = Factory::getDbo()->getTableList();

        if ( ! in_array($prefix.'quix', $tables)) {
            return;
        }

        $query = "SHOW COLUMNS FROM `#__quix` LIKE 'catid'";
        $db->setQuery($query);
        $column = (object) $db->loadObject();
        if (empty($column) or empty($column->Field)) {
            $query = /** @lang text */
                "
				ALTER TABLE  `#__quix`
				ADD `catid` int(11) NOT NULL AFTER  `title`,
				ADD `version` int(10) unsigned NOT NULL DEFAULT '1' AFTER `params`,
				ADD `hits` int(11) NOT NULL AFTER `version`,
				ADD `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.' AFTER `hits`,
				ADD INDEX `idx_access` (`access`),
				ADD INDEX `idx_catid` (`catid`),
				ADD INDEX `idx_state` (`state`),
				ADD INDEX `idx_createdby` (`created_by`),
				ADD INDEX `idx_xreference` (`xreference`);
				";
            $db->setQuery($query);
            $db->execute();
        }
    }

    public function cleanQuixCache()
    {
        $session = Factory::getSession();
        $session->set('quix_install_cleancache', 1);
    }

    /**
     * Function to perform changes during install
     *
     * @param  JInstallerAdapterComponent  $parent  The class calling this method
     *
     * @return  void
     *
     * @since   3.4
     */
    public function postflight($parent)
    {
        self::enablePlugins();
        self::insertMissingUcmRecords();

        // clean quix cache
        self::cleanQuixCache();

        if ($this->migration) {
            // now uninstall all the extensions
            $this->uninstallOldExtensions();
        }

        $this->updateDBfromOLD();
        ?>
        <!-- INSTALL PAGE -->
      <?php
       $layout = new JLayoutFile('install', JPATH_ADMINISTRATOR . '/components/com_quix/layouts/blocks');
       echo $layout->render();
      ?>

        <?php
    }

    /**
     * enable necessary plugins to avoid bad experience
     *
     * @since 3.0.0
     */
    public function enablePlugins()
    {
        $db  = Factory::getDBO();
        $sql = /** @lang text */
            "SELECT `element`,`folder` from `#__extensions` WHERE `type` = 'plugin' AND `folder` in ('quix', 'finder', 'system', 'content', 'editors-xtd', 'quickicon') AND `name` like '%quix%' AND `enabled` = '0'";
        $db->setQuery($sql);
        $plugins = $db->loadObjectList();
        if (count($plugins)) {
            foreach ($plugins as $key => $value) {
                if ($value->folder == 'finder' or $value->folder == 'system' or $value->folder == 'editors-xtd') {
                    $query = $db->getQuery(true);
                    $query->update($db->quoteName('#__extensions'));
                    $query->set($db->quoteName('enabled').' = '.$db->quote('1'));
                    $query->where($db->quoteName('type').' = '.$db->quote('plugin'));
                    $query->where($db->quoteName('element').' = '.$db->quote($value->element));
                    $query->where($db->quoteName('folder').' = '.$db->quote($value->folder));
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }

        $sql = /** @lang text */
            "SELECT `element`,`folder`, `enabled` from `#__extensions` WHERE `type` = 'plugin' AND `folder` ='system' AND `element` = 'seositeattributes' AND `enabled` = '0'";
        $db->setQuery($sql);
        $plugins = $db->loadObjectList();
        if ( ! count($plugins)) {
            return false;
        }
        foreach ($plugins as $key => $value) {
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__extensions'));
            $query->set($db->quoteName('enabled').' = '.$db->quote('1'));
            $query->where($db->quoteName('type').' = '.$db->quote('plugin'));
            $query->where($db->quoteName('element').' = '.$db->quote($value->element));
            $query->where($db->quoteName('folder').' = '.$db->quote($value->folder));
            $db->setQuery($query);
            $db->execute();
        }

        return true;
    }

    /**
     * Method to insert missing records for the UCM tables
     *
     * @return bool
     *
     * @since   3.4.1
     */
    public function insertMissingUcmRecords()
    {
        // Insert the rows in the #__content_types table if they don't exist already
        $db = Factory::getDbo();

        // Get the type ID for a xDoc
        $query = $db->getQuery(true);
        $query->select($db->quoteName('type_id'))
              ->from($db->quoteName('#__content_types'))
              ->where($db->quoteName('type_alias').' = '.$db->quote('com_quix.page'));
        $db->setQuery($query);

        $docTypeId = $db->loadResult();

        // Set the table columns to insert table to
        $columnsArray = [
            $db->quoteName('type_title'),
            $db->quoteName('type_alias'),
            $db->quoteName('table'),
            $db->quoteName('rules'),
            $db->quoteName('field_mappings'),
            $db->quoteName('router'),
            $db->quoteName('content_history_options'),
        ];

        // If we have no type id for com_xdocs.doc insert it
        if ( ! $docTypeId) {
            // Insert the data.
            $query->clear();
            $query->insert($db->quoteName('#__content_types'));
            $query->columns($columnsArray);
            $query->values(
                $db->quote('Quix Page').', '
                .$db->quote('com_quix.page').', '
                .$db->quote('{"special":{"dbtable":"#__quix","key":"id","type":"Page","prefix":"QuixTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}').', '
                .$db->quote('').', '
                .$db->quote('{"common":{"core_content_item_id":"id","core_title":"title","core_state":"state","core_body":"description", "core_hits":"hits","core_access":"access", "core_params":"params", "core_metadata":"metadata", "core_language":"language", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_xreference":"xreference", "asset_id":"null"}, "special":{}}').', '
                .$db->quote('QuixFrontendHelperRoute::getPageRoute').', '
                .$db->quote('{"formFile":"administrator\\/components\\/com_quix\\/models\\/forms\\/page.xml", "hideFields":["asset_id","checked_out","checked_out_time"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"], "convertToInt":["publish_up", "publish_down", "featured", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}')
            );

            $db->setQuery($query);
            $db->execute();
        }

        return true;
    }
}
