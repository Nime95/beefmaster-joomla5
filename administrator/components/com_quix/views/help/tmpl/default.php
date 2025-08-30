<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

  defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
// Include the HTML helpers.
\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
// Load toolbar
$layout     = new JLayoutFile('blocks.toolbar');
echo $layout->render(['active' => '']);
?>
<div class="quix qx-container qx-text-small">
    <div class="qx-margin qx-card qx-card-primary qx-card-small qx-flex qx-flex-between qx-flex-middle qx-padding qx-box-shadow-remove qx-border-remove">
      <div class="qx-flex qx-flex-middle">
        <div>
          <span class="qxuicon-thumbs-up qx-text-primary qx-margin-medium-right qx-margin-small-left" style="font-size: 50px;"></span>
        </div>
        <div style="latter-spacing: 0.5px">
          <h3 class="qx-text-bold qx-margin-remove" ><?php echo Text::_("COM_QUIX_RATE_US") ?></h3>
          <p style="color: white;" class="qx-font-500 qx-margin-small-top" ><?php echo Text::_("COM_QUIX_RATE_US_DESC") ?></p>
        </div>
      </div>
      <div>
        <a
          class="qx-button qx-button-primary"
          style="color: var(--qx-admin-clr-primary); background: white !important; padding-block: 12px;"
          href="https://extensions.joomla.org/extension/quix/" target="_blank"><span class="qxuicon-external-link qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_RATE_US_BUTTON") ?></a>
      </div>
    </div>
    <div class="qx-child-width-1-2@s" qx-grid>
      <div>
        <div class="qx-card qx-card-default qx-background-white qx-card-body qx-box-shadow-remove qx-border-remove">
          <?php echo $this->loadTemplate('req') ?>
        </div>
      </div>
      <div style="padding-left: 20px" >
        <!-- Documentation -->
        <div class="qx-card qx-card-default qx-card-small qx-padding qx-background-white qx-box-shadow-remove qx-border-remove">
          <div class="qx-flex-middle" qx-grid>
            <div class="qx-width-expand">
              <div class="qx-flex-middle" qx-grid>
                <div><span class="qxuicon-book" style="font-size: 35px;"></span></div>
                <div>
                  <h3 class="qx-font-500 qx-margin-remove"><?php echo JText::_("COM_QUIX_5_DOCUMENTATION"); ?></h3>
                  <p class="qx-margin-small-top"><?php echo JText::_("COM_QUIX_DOC_DESC"); ?></p>
                </div>
              </div>
            </div>
            <div class="qx-width-1-3 qx-text-right">
              <a class="qx-button qx-button-primary qx-button-small qx-width-1-1" href="https://www.themexpert.com/docs" target="_blank"><?php echo JText::_("COM_QUIX_5_DOCUMENTATION"); ?></a>
            </div>
          </div>
        </div>
        <!-- Community -->
        <div class="qx-card qx-card-default qx-card-small qx-background-white qx-margin-top qx-padding qx-box-shadow-remove qx-border-remove">
          <div class="qx-flex-middle" qx-grid>
            <div class="qx-width-expand">
              <div class="qx-flex-middle" qx-grid>
                <div><span class="qxuicon-users" style="font-size: 35px;"></span></div>
                <div>
                  <h3 class="qx-font-500 qx-margin-remove"><?php echo JText::_("COM_QUIX_COMMUNITY"); ?></h3>
                  <p class="qx-margin-small-top"><?php echo JText::_("COM_QUIX_COMMUNITY_DESC"); ?></p>
                </div>
              </div>
            </div>
            <div class="qx-width-1-3 qx-text-right">
              <a class="qx-button qx-button-primary qx-button-small qx-width-1-1" href="https://www.facebook.com/groups/QuixUserGroup/" target="_blank"><?php echo JText::_("COM_QUIX_JOIN_TODAY"); ?></a>
            </div>
          </div>
        </div>
        <!-- Support -->
        <div class="qx-card qx-card-default qx-card-small qx-background-white qx-padding qx-margin-top qx-box-shadow-remove qx-border-remove">
          <div class="qx-flex-middle" qx-grid>
            <div class="qx-width-expand">
              <div class="qx-flex-middle" qx-grid>
                <div><span class="qxuicon-life-ring" style="font-size: 35px;"></span></div>
                <div>
                  <h3 class="qx-font-500 qx-margin-remove"><?php echo JText::_("COM_QUIX_SUPPORT"); ?></h3>
                  <p class="qx-margin-small-top"><?php echo JText::_("COM_QUIX_SUPPORT_DESC"); ?></p>
                </div>
              </div>
            </div>
            <div class="qx-width-1-3 qx-text-right">
              <a class="qx-button qx-button-primary qx-button-small qx-width-1-1" href="https://www.themexpert.com/support" target="_blank"><?php echo JText::_("COM_QUIX_GET_SUPPORT"); ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
<div>

  <?php echo QuixHelper::getFooterLayout(); ?>

