<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Message configuration model.
 *
 * @since  1.6
 */
class QuixModelCodes extends JModelForm
{

    /**
     * Method to get a single record.
     *
     * @return  mixed  Object on success, false on failure.
     *
     * @since   1.6
     */
    public function getItem()
    {
        $item = new JObject;

        $db    = $this->getDbo();

        $query = $db->getQuery(true);
        $query->select('*')->from('#__quix_configs');

        $db->setQuery($query);
        $params = $db->loadObjectList();
        foreach ($params as $key => $param)
        {
            $item->{$param->name} = $param->params;
        }

        if(!isset($item->activated)){
            $item->activated = 0;
        }

        $this->preprocessData('com_quix.codes', $item);

        return $item;
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = [], $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_quix.codes', 'codes', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @throws Exception
     * @since   1.6
     */
    public function save(array $data)
    {
        if (count($data))
        {
            // try to save them on config table
            $this->saveToConfig($data);
        }

        return true;
    }

    public function saveToConfig($data)
    {
        $result = [];
        // dont allow empty request
        $db    = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__quix_configs');
        $db->setQuery($query);
        $config = $db->loadObjectList();

        $keys = [];
        foreach ($config as $key => $item)
        {
            $keys[] = $item->name;
        }

        foreach ($data as $key => $value)
        {
            // Create an object for the record we are going to update.
            $obj         = new stdClass();
            $obj->name   = $key;
            $obj->params = $value;

            if (in_array($key, $keys))
            {
                // Update their details in the users table using id as the primary key.
                $result[] = Factory::getDbo()->updateObject('#__quix_configs', $obj, 'name');
            }
            else
            {
                // Insert the object into the user obj table.
                $result[] = Factory::getDbo()->insertObject('#__quix_configs', $obj);
            }
        }

        if (in_array(false, $result))
        {
            return false;
        }

        return true;
    }

    /**
     * Custom clean the cache of com_content and content modules
     *
     * @param   null     $group      The cache group
     * @param   integer  $client_id  The ID of the client
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function cleanCache($group = null, $client_id = 0)
    {
        //QuixHelper::cleanCache();
        QuixHelperCache::cleanCache();
        parent::cleanCache('com_quix');
        parent::cleanCache('mod_quix');
        parent::cleanCache('lib_quix');
    }
}
