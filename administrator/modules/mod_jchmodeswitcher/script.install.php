<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die('Restricted Access');
// phpcs:enable PSR1.Files.SideEffects

class Mod_JchmodeswitcherInstallerScript extends InstallerScript
{
    protected $allowDowngrades = true;

    public function postflight(string $type)
    {
        if ($type == 'install' || $type == 'update') {
            $instances = $this->getInstances(true);

            if (empty($instances)) {
                $module = [
                    'id' =>  0,
                    'asset_id' => 0,
                    'language' => '*',
                    'note' => '',
                    'published' => 1,
                    'assignment' => 0,
                    'showtitle' => 1,
                    'content' => '',
                    'client_id' => 1,
                    'module' => $this->extension,
                    'position' => 'status',
                    'access' => 2,
                    'title' => Text::_(strtoupper($this->extension)),
                    'params' => []
                ];
            } else {
                $module = [
                    'id' => $instances[0],
                    'published' => 1,
                    'position' => 'status'
                ];
            }


            if (!empty($instances) && !$this->inModulesMenuTable($instances[0])) {
                $model = Factory::getApplication()
                    ->bootComponent('com_modules')
                    ->getMVCFactory()
                    ->createModel('Module', 'Administrator', ['ignore_request' => true]);
                if (!$model->save($module)) {
                    Factory::getApplication()->enqueueMessage(
                        Text::sprintf('MOD_JCHMODESWITCHER_INSTALL_ERROR', $model->getError())
                    );
                }

                $this->addToModulesMenuTable();
            }
        }

        return true;
    }

    private function inModulesMenuTable(mixed $id): bool
    {
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true)
                    ->select($db->quoteName('moduleid'))
                    ->from('#__modules_menu')
                    ->where($db->quoteName('moduleid') . ' = :id');
        $query->bind(':id', $id);
        $db->setQuery($query, 0, 1);

        return (bool)$db->loadResult();
    }

    private function addToModulesMenuTable(): void
    {
        try {
            $id = $this->getInstances(true)[0];
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true)
              ->insert('#__modules_menu')
              ->columns([$db->quoteName('moduleid'), $db->quoteName('menuid')])
              ->values((int)$id . ',  0');
            $db->setQuery($query);
            $db->execute();
        } catch (Exception $e) {
        }
    }
}
