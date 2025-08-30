<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Include the HTML helpers.
\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
\Joomla\CMS\HTML\HTMLHelper::_('behavior.formvalidator');
\Joomla\CMS\HTML\HTMLHelper::_('behavior.keepalive');

Factory::getDocument()->addScriptDeclaration("
		Joomla.submitbutton = function(task)
		{
			if (task == 'config.cancel' || document.formvalidator.isValid(document.getElementById('config-form')))
			{
				Joomla.submitform(task, document.getElementById('config-form'));
			}
		};
");

$layout = new JLayoutFile('blocks.toolbar');
echo $layout->render(['active' => '']);
?>
<div class="quix qx-container qx-margin-small qx-text-small qx-margin-remove-top">

  <?php echo QuixHelper::randerSysMessage(); ?>

  <div class="qx-card qx-card-default qx-box-shadow-remove qx-border-remove qx-background-white">
    <form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=config'); ?>" method="post"
          name="adminForm" id="adminForm" class="form-validate form-horizontal qx-admin-box">

      <div class="qx-card-header">
        <h3 class="qx-card-title"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_LICENSE_TITLE'); ?></h3>
      </div>

      <div class="qx-card-body">
        <div class="qx-grid-column-divider" qx-grid>
          <div class="qx-width-expand@s">
            <div class="qx-margin">

              <?php if (isset($this->item->activated) && $this->item->activated == 1): ?>
                <div class="qx-margin" data-message>
                  <div class="qx-alert-success" qx-alert>
                    <!--                    <a class="qx-alert-close" qx-close></a>-->
                    <p><strong>Congratulations!</strong> Your license has been Activated. You can use Quix Pro features
                      now. Enjoy
                    </p>
                  </div>
                </div>

                <div class="qx-margin">
                  <div class="qx-hidden">
                    <?php echo $this->form->getInput('username'); ?>
                    <?php echo $this->form->getInput('key'); ?>

                    <input type="hidden" name="jform[activated]" id="jform_activated" value="0">
                  </div>

                  <button class="qx-button qx-button-danger qx-button-small" type="submit">
                    <?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_DEACTIVATE'); ?>
                  </button>
                </div>
              <?php else: ?>

              <div class="qx-margin" data-message></div>

              <div class="qx-margin">
                  <label class="qx-form-label" for="form-stacked-text">
                    <?php echo $this->form->getLabel('username'); ?>
                  </label>
                  <?php echo $this->form->getInput('username'); ?>
                </div>

                <div class="qx-margin">
                  <label class="qx-form-label" for="form-stacked-text">
                    <?php echo $this->form->getLabel('key'); ?>
                  </label>
                  <?php echo $this->form->getInput('key'); ?>
                </div>

                <div class="qx-margin">
                  <button id="activateBtn" class="qx-button qx-button-primary" type="button" data-validation-submit>
                    <?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_ACTIVATE'); ?>
                  </button>
                </div>

                <?php echo $this->form->getInput('activated'); ?>

              <?php endif; ?>
            </div>


          </div>

          <div class="qx-width-1-3@s">
            <div class="qx-alert-primary" qx-alert>
              <a class="qx-alert-close" qx-close></a>
              <p><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_MY_SETTINGS_DESC'); ?></p>
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="task" value="config.save"/>
      <input type="hidden" name="view" value="config"/>
      <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
    </form>
  </div>
</div>
