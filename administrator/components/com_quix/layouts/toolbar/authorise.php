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

$session = Factory::getSession();
$status  = $session->get('quix-notification-verifyLicense', 'open');
if ($status === 'collapse') {
    return '';
}

$text = \Joomla\CMS\Language\Text::_('COM_QUIX_TOOLBAR_ACTIVATION');
?>
  <div style="background: rgb(217 248 255); padding-block: 15px; border-bottom: #1px solid #80808042" class="qx-admin-box qx-alert qx-color-white qx-background-secondary qx-margin-remove qx-clearfix qx-padding-block-x-small"
       style="margin: -5px 0px 20px;" qx-alert>
    <a class="qx-alert-close" data-session="quix-notification-verifyLicense" qx-close></a>
    <div class="qx-container">
      <p style="color: #434343;" >

        <span style="gap: 0.5rem" class="qx-flex">
          <span
            style="background: rgb(77 59 255); padding: 5px 15px; font-size: 12px; font-weight: 500;"
            class="qx-border-rounded qx-label qx-label-danger qx-text-light qx-margin-small-right"
          >
           <?php echo Joomla\CMS\Language\Text::_('COM_QUIX_LICENCE_MISSING'); ?>
          </span>
          <span>
          <?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_AUTHORISE_MESSAGE'); ?>
          <i class="qxuicon-arrow-down qx-margin-small-right"></i>
          </span>
        </span>
        <!--<a rel="{handler:'iframe', size:{x:700,y:350}}"-->
        <!--   href="index.php?option=com_quix&amp;view=config&amp;tmpl=component"-->
        <!--   title="--><?php //echo $text; ?><!--"-->
        <!--   class="quixSettings qx-button qx-button-danger qx-button-small" id="mySettings2">-->
        <!--  <span class="icon-lock"></span> --><?php //echo $text; ?>
        <!--</a>-->
      </p>

    </div>
  </div>
<?php if (Factory::getApplication()->input->get('action', false)): ?>
  <script type="text/javascript">
      setTimeout(function() {
          jQuery('.quixSettings')[0].click();
      }, 3000);
  </script>
<?php endif;
