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
$activated  = QuixHelperLicense::isProActivated();
echo $layout->render(['active' => '']);
?>
<style>
    pre{
        font: 12px/1.5 sans,monospace;
        color: #666;
        -moz-tab-size: 4;
        tab-size: 4;
        overflow: auto;
        padding: 25px;
        border: 0 solid #e5e5e5;
        border-radius: 0;
        background: #f8f8f8;
        margin: 0px auto 20px;
    }
</style>
<div class="quix qx-container">
<?php echo QuixHelper::randerSysMessage(); ?>

<div class="qx-grid qx-grid-small" qx-grid="">
  <!-- <div class="qx-width-expand@m qx-first-column">
    <div class="qx-card qx-card-default qx-padding qx-box-shadow-remove qx-border-remove">
     <h3 class="qx-margin-remove qx-relative">
      Custom Code.
      <sup class="qx-pro-tag qx-label-danger" >Pro Feature</sup>
    </h3>
     <p>Add custom code to your website.</p>
    </div>
  </div> -->
    <div class="qx-width-expand@m qx-first-column ">
      <div id="quix-codes-wrapper" class="qx-card qx-card-default qx-box-shadow-remove qx-border-remove qx-background-white qx-relative">
        <!-- <div class="qx-free-overlay">
          <div>
            <i class="qxuicon-lock"></i>
            <h3>This is a pro feature</h3>
            <p>You need to upgrade to PRO in-order to use this</p>
          </div>
        </div> -->
          <div class="qx-card-header qx-padding">
              <h3 class="qx-card-title qx-margin-remove qx-font-500"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_DESC'); ?></h3>
              <p class="qx-margin-small-top qx-margin-medium-bottom"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_PRE_TEXT'); ?></p>

              <div class="qx-alert">
                  <p class="qx-text-bold qx-margin-remove-bottom"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_WARNING_TITLE'); ?></p>
                  <p class="qx-text-danger qx-margin-small-top"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_WARNING_DESC'); ?></p>
              </div>
          </div>

          <form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=codes'); ?>" method="post"
                name="headCodeForm" id="headCode" class="form-validate form-horizontal qx-admin-box">

              <div class="qx-card-muted qx-card-body qx-padding-remove-bottom">
                  <div class="qx-flex qx-flex-between">
                      <div class="qx-width-expand">
                          <h4 class="qx-margin-remove qx-font-500"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_HEAD_CODE'); ?></h4>
                          <p class="qx-margin-small-top"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_HEAD_CODE_DESC'); ?></p>
                      </div>
                      <div>
                        <button style="padding: 0.5rem 1rem;" type="submit" class="qx-button-primary qx-border-rounded"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_SAVE_BUTTON'); ?></button>
                      </div>
                  </div>
                  <div class="code-editor-wrapper" style="display:none;">
                      <?php
                        if ($activated) {
                          echo $this->form->getInput('head_code');
                        } else {
                          echo '<img alt="qx-cod-lock" src="' . QuixAppHelper::getQuixMediaUrl() . '/images/code-lock.jpg" />';
                        }
                    ?>
                  </div>
              </div>
              <input type="hidden" name="task" value="codes.save"/>
              <input type="hidden" name="section" value="head"/>
              <input type="hidden" name="view" value="config"/>
              <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
          </form>

          <hr class="qx-divider" />

          <form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=codes'); ?>" method="post"
                name="footerCodeForm" id="footerCode" class="form-validate form-horizontal qx-admin-box">

              <div class="qx-card-muted qx-card-body qx-padding-remove-top qx-padding-remove-bottom">
                  <div class="qx-flex qx-flex-between">
                      <div class="qx-width-expand">
                          <h4 class="qx-margin-remove qx-font-500"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_FOOTER_CODE'); ?></h4>
                          <p class="qx-margin-small-top"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_FOOTER_CODE_DESC'); ?></p>
                      </div>
                      <div>
                        <button style="padding: 0.5rem 1rem;" type="submit" class="qx-button-primary qx-border-rounded"><?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_CODES_SAVE_BUTTON'); ?></button>
                      </div>
                  </div>
                  <div class="code-editor-wrapper" style="display:none;">
                    <?php
                      if ($activated) {
                        echo $this->form->getInput('footer_code');
                      } else {
                        echo '<img alt="qx-cod-lock" class="qx-margin-medium-bottom" src="' . QuixAppHelper::getQuixMediaUrl() . '/images/code-lock.jpg" />';
                      }
                    ?>

                  </div>
              </div>
              <input type="hidden" name="task" value="codes.save"/>
              <input type="hidden" name="section" value="footer"/>
              <input type="hidden" name="view" value="config"/>
              <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
          </form>
      </div>
    </div>

    <div style="padding-left: 20px" class="qx-width-1-4@m">
        <?php

        if(!$activated): ?>
          <div class="qx-card qx-padding-medium qx-margin qx-background-white qx-border-remove">
              <div class="qx-relative">
                  <div class="qx-text-center">
                      <img class="qx-go-pro-img" src="<?php echo QuixAppHelper::getQuixMediaUrl().'/images/go-pro.png' ?>" alt="Pro image"/>
                      <h4 class="qx-font-500"><?php echo Text::_("COM_QUIX_CONVERSION_TITLE") ?></h4>
                      <p class="qx-subtitle-text"><?php echo Text::_("COM_QUIX_CONVERSION_DESC") ?></p>
                      <a class="qx-button qx-label-danger qx-padding-small qx-border-rounded qx-padding-inline-small qx-padding-block-x-small qx-hover-clr-white" href="https://www.themexpert.com/quix-pagebuilder?utm_medium=button&utm_campaign=quix-pro&utm_source=admin-panel&utm_content=upgrade-now" target="_blank">
                        <svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.8306 3.443C12.6449 3.16613 12.3334 3 12.0001 3C11.6667 3 11.3553 3.16613 11.1696 3.443L7.38953 9.07917L2.74781 3.85213C2.44865 3.51525 1.96117 3.42002 1.55723 3.61953C1.15329 3.81904 0.932635 4.26404 1.01833 4.70634L3.70454 18.5706C3.97784 19.9812 5.21293 21 6.64977 21H17.3504C18.7872 21 20.0223 19.9812 20.2956 18.5706L22.9818 4.70634C23.0675 4.26404 22.8469 3.81904 22.4429 3.61953C22.039 3.42002 21.5515 3.51525 21.2523 3.85213L16.6106 9.07917L12.8306 3.443Z" fill="#fff"></path> </g></svg>
                        <span style="margin-left: 10px"><?php echo Text::_("COM_QUIX_POR_MESSAGE"); ?></span>
                      </a>
                  </div>

                <ul style="color: var(--qx-admin-text-clr-dark); font-weight: 500; margin-bottom: 10px" class="qx-list">
                  <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo Text::_("COM_QUIX_JSON_IMPORT_EXPORT") ?></li>
                  <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo Text::_("COM_QUIX_SEO_SETTINGS") ?></li>
                  <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo Text::_("COM_QUIX_CUSTOM_CODE") ?></li>
                  <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo Text::_("COM_QUIX_COPY_PASTE") ?></li>
                </ul>
              </div>

          </div>
        <?php endif; ?>

        <div class="qx-card qx-padding-medium qx-background-white qx-border-remove">
            <ul class="qx-iconnav qx-iconnav-vertical qx-list-divider">
                <li>
                    <a href="https://www.themexpert.com/support" target="_blank" qx-icon="icon: plus" class="qx-icon">
                        <span class="qx-icon-button">
                            <svg width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" stroke-width="1.1" cx="10" cy="10" r="9"></circle><circle cx="9.99" cy="14.24" r="1.05"></circle><path fill="none" stroke="#000" stroke-width="1.2" d="m7.72,7.61c0-3.04,4.55-3.06,4.55-.07,0,.95-.91,1.43-1.49,2.03-.48.49-.72.98-.78,1.65-.01.13-.02.24-.02.35"></path></svg>
                        </span>
                        <span class="qx-text-default qx-margin-small-left"><?php echo Text::_("COM_QUIX_HELP_CENTER") ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://www.youtube.com/@ThemeXpert/videos" target="_blank" qx-icon="icon: plus" class="qx-icon">
                        <span class="qx-icon-button">
                            <svg width="20" height="20" viewBox="0 0 20 20"><path d="M15,4.1c1,0.1,2.3,0,3,0.8c0.8,0.8,0.9,2.1,0.9,3.1C19,9.2,19,10.9,19,12c-0.1,1.1,0,2.4-0.5,3.4c-0.5,1.1-1.4,1.5-2.5,1.6 c-1.2,0.1-8.6,0.1-11,0c-1.1-0.1-2.4-0.1-3.2-1c-0.7-0.8-0.7-2-0.8-3C1,11.8,1,10.1,1,8.9c0-1.1,0-2.4,0.5-3.4C2,4.5,3,4.3,4.1,4.2 C5.3,4.1,12.6,4,15,4.1z M8,7.5v6l5.5-3L8,7.5z"></path></svg>
                        </span>
                        <span class="qx-text-default qx-margin-small-left"><?php echo Text::_("COM_QUIX_YOUTUBE") ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://www.themexpert.com/blog" target="_blank" qx-icon="icon: plus" class="qx-icon">
                        <span class="qx-icon-button">
                            <svg width="20" height="20" viewBox="0 0 20 20"><ellipse fill="none" stroke="#000" cx="10" cy="4.64" rx="7.5" ry="3.14"></ellipse><path fill="none" stroke="#000" d="M17.5,8.11 C17.5,9.85 14.14,11.25 10,11.25 C5.86,11.25 2.5,9.84 2.5,8.11"></path><path fill="none" stroke="#000" d="M17.5,11.25 C17.5,12.99 14.14,14.39 10,14.39 C5.86,14.39 2.5,12.98 2.5,11.25"></path><path fill="none" stroke="#000" d="M17.49,4.64 L17.5,14.36 C17.5,16.1 14.14,17.5 10,17.5 C5.86,17.5 2.5,16.09 2.5,14.36 L2.5,4.64"></path></svg>
                        </span>
                        <span class="qx-text-default qx-margin-small-left"><?php echo Text::_("COM_QUIX_BLOG") ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/groups/QuixUserGroup" target="_blank" qx-icon="icon: plus" class="qx-icon">
                        <span class="qx-icon-button">
                            <svg width="20" height="20" viewBox="0 0 20 20"><path d="M11,10h2.6l0.4-3H11V5.3c0-0.9,0.2-1.5,1.5-1.5H14V1.1c-0.3,0-1-0.1-2.1-0.1C9.6,1,8,2.4,8,5v2H5.5v3H8v8h3V10z"></path></svg>
                        </span>
                        <span class="qx-text-default qx-margin-small-left"><?php echo Text::_("COM_QUIX_FACEBOOK_COMMUNITY") ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

</div>

<?php echo QuixHelper::getFooterLayout(); ?>

