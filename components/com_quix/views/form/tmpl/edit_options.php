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

?>
<div id="hidden-for-editor" style="display: none!important;">
    <?php //echo $this->form->renderField('editor'); ?>

    <?php
    $conf   = Factory::getConfig();
    $editor = $conf->get('editor');
    if ($editor == 'jce') {
        require_once(JPATH_ADMINISTRATOR.'/components/com_jce/includes/base.php');

        wfimport('admin.models.editor');
        $editor   = new WFModelEditor();
        $app      = Factory::getApplication();
        $settings = $editor->getEditorSettings();
        $app->triggerEvent('onBeforeWfEditorRender', array(&$settings));
        echo $editor->render($settings);
    } else {
        QuixFrontendHelperEditor::loadTinyMce();
    }
    ?>


    <?php if (Factory::getUser()->authorise('core.admin', 'quix')) : ?>
        <?php echo $this->form->getInput('rules'); ?>
    <?php endif; ?>

    <?php foreach ($this->form->getGroup('params') as $field) : ?>
        <?php echo $field->renderField(); ?>
    <?php endforeach; ?>

    <?php foreach ($this->form->getGroup('metadata') as $field) : ?>
        <?php echo $field->renderField(); ?>
    <?php endforeach; ?>

    <?php echo $this->form->getInput('menutype'); ?>
    <?php echo $this->form->getInput('templatestyle'); ?>
    <?php echo $this->form->getInput('conditions'); ?>
    <?php echo $this->form->getInput('state'); ?>
    <?php echo $this->form->getInput('access'); ?>
    <?php echo $this->form->getInput('language'); ?>

    <style type="text/css" id="page-custom-code-style"></style>
    <script type="text/javascript" id="page-custom-code-script"></script>
</div>
