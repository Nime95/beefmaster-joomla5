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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;

$session = Factory::getSession();
$status  = $session->get('welcome-toolbar', 'open');
$activated = QuixHelperLicense::isProActivated();
?>
<style>
    #qx-welcome-v3 {
      box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
      background: white;
      border: none;
    }
</style>
<div id="qx-welcome-v3-wrapper" class="qx-position-relative qx-background-white qx-padding qx-border-rounded qx-margin-medium-top qx-margin-bottom">
    <div class="qx-grid qx-flex qx-flex-middle" qx-grid="">

        <div class="qx-width-expand@m qx-first-column">
           <div class="qx-relative">
               <h3 style="line-height: 1" class="qx-margin-remove qx-font-500"><?php echo Text::_('COM_QUIX_WELCOME_MESSAGE'); ?></h3>
               <p class="qx-margin-small-top qx-subtitle-text qx-margin-bottom"><?php echo Text::_('COM_QUIX_WELCOME_DESC'); ?></p>
               <?php
                if(!$activated) {
                  echo '<a class="qx-button qx-label-danger qx-padding-small qx-border-rounded qx-padding-inline-small qx-padding-block-x-small qx-margin-right qx-hover-clr-white" href="https://www.themexpert.com/quix-pagebuilder?utm_medium=button&utm_campaign=quix-pro&utm_source=admin-panel&utm_content=upgrade-now" target="_blank">
                            <svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.8306 3.443C12.6449 3.16613 12.3334 3 12.0001 3C11.6667 3 11.3553 3.16613 11.1696 3.443L7.38953 9.07917L2.74781 3.85213C2.44865 3.51525 1.96117 3.42002 1.55723 3.61953C1.15329 3.81904 0.932635 4.26404 1.01833 4.70634L3.70454 18.5706C3.97784 19.9812 5.21293 21 6.64977 21H17.3504C18.7872 21 20.0223 19.9812 20.2956 18.5706L22.9818 4.70634C23.0675 4.26404 22.8469 3.81904 22.4429 3.61953C22.039 3.42002 21.5515 3.51525 21.2523 3.85213L16.6106 9.07917L12.8306 3.443Z" fill="#fff"></path> </g></svg>
                            <span style="margin-left: 10px">' . Text::_("COM_QUIX_POR_MESSAGE") . '</span>
                          </a>';
                }
               ?>
               <a class="qx-button qx-button-default qx-padding-small qx-border-rounded qx-padding-block-x-small" target="_blank" href="https://www.youtube.com/watch?v=f5QniFn9lxQ">
                   <span class="qx-margin-small-right qx-icon" qx-icon="video-camera"><svg width="20" height="20" viewBox="0 0 20 20"><polygon fill="none" stroke="#000" points="19.5 5.9 19.5 14.1 14.5 10.4 14.5 15.5 .5 15.5 .5 4.5 14.5 4.5 14.5 9.6 19.5 5.9"></polygon></svg></span>
                  <?php echo Text::_("COM_QUIX_WATCH_GUIDE"); ?>
               </a>
           </div>
        </div><div class="qx-width-1-3@m">
            <a href="https://www.youtube.com/watch?v=f5QniFn9lxQ" target="_blank" class="qx-video-player qx-border-rounded">
              <img src="<?php echo QuixAppHelper::getQuixMediaUrl().'/images/quix-5-builder.png' ?>" alt="Builder image preview"/>

               <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg>
            </a>
        </div>

    </div>
</div>

<!-- TEST CODE FOR WELCOME PAGE -->
<?php
// $layout = new JLayoutFile('blocks.install');
// echo $layout->render();
?>
