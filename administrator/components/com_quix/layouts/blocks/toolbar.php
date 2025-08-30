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
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

$active = isset($displayData['active']) ? $displayData['active'] : 'pages';
$input  = Factory::getApplication()->input;
$activated  = QuixHelper::isProActivated();
$links  = [
    'pages'              => [
        'title' => Text::_('COM_QUIX_PAGES'),
        'link'  => 'index.php?option=com_quix&view=pages',
        'icon'  => '',
    ],
    'collections.header' => [
        'title' => Text::_('COM_QUIX_HEADER') . ($activated ? '' : '<span style="--_triangle-color: #f0506e" class="qx-label qx-label-danger qx-tooltip-triangle">PRO</span>'),
        'link'  => 'index.php?option=com_quix&view=collections&filter_collection=header',
        'icon'  => '',
    ],
    'collections.footer' => [
        'title' => Text::_('COM_QUIX_FOOTER') . ($activated ? '' : '<span style="--_triangle-color: #f0506e" class="qx-label qx-label-danger qx-tooltip-triangle">PRO</span>'),
        'link'  => 'index.php?option=com_quix&view=collections&filter_collection=footer',
        'icon'  => '',
    ],
    'collections.all'    => [
        'title' => Text::_('COM_QUIX_COLLECTIONS'),
        'link'  => 'index.php?option=com_quix&view=collections&filter_collection=all',
        'icon'  => '',
    ],
    'codes.all'    => [
        'title' => Text::_('COM_QUIX_CODES'),
        'link'  => 'index.php?option=com_quix&view=codes',
        'icon'  => 'qxuicon-code',
    ],
];


$activatedText = \Joomla\CMS\Language\Text::_('COM_QUIX_TOOLBAR_ACTIVATION');
if ($activated) {
    $activatedText = \Joomla\CMS\Language\Text::_('COM_QUIX_TOOLBAR_ACTIVATION_DONE');
}
$uri       = JUri::getInstance();
$returnUrl = base64_encode($uri->toString());

$session = Factory::getSession();
$status  = $session->get('guide-quix', 'show');
$tourComplete = $input->cookie->get('guide-quix', null);

// show_tour_guide
$config = ComponentHelper::getParams('com_quix');
$show_tour_guide = $tourComplete = $config->get('guide-quix', 'show');

if ($tourComplete !== 'hide' && $status !== 'hide') {
    Factory::getDocument()->addScript("https://cdn.jsdelivr.net/npm/shepherd.js@5.0.1/dist/js/shepherd.js");
    Factory::getDocument()->addScript(
        JUri::root(true).'/administrator/components/com_quix/assets/guide.js'
    );
}

// we will show the notice once in lifetime for now. Later we will add a setting to show/hide the notice
// maybe on every update install, we can forcefully set it to show
QuixHelper::setComponentParams('guide-quix', 'hide');
?>
<div class="qx-toolbar qx-background-white">
    <?php echo QuixHelperLayout::renderGlobalMessage(); ?>

  <nav class="qx-container qx-navbar qx-padding-block-x-small" qx-navbar>
    <div class="qx-flex">

      <a class="qx-navbar-toggle qx-hidden@s" href="#toolbar-mobile-menu" qx-toggle>
        <span class="qxuicon-bars"></span>
      </a>

      <a class="qx-navbar-item qx-logo" href="index.php?option=com_quix&view=pages">
        <img class="qx-margin-right" width="30" height="30" src="<?php
        echo QuixAppHelper::getQuixMediaUrl().'/images/quix5-logo.png' ?>" alt="Quix Logo"
             width="40">
      </a>

      <ul class="qx-navbar-nav qx-visible@s qx-navar-container">
          <?php
          foreach ($links as $key => $link): ?>
            <li class="<?php
            echo $active === $key ? 'qx-active' : ''; ?>">
              <a href="<?php
              echo $link['link']; ?>">
                  <?php
                  if ( ! empty($link['icon'])): ?>
                    <em class="<?php
                    echo $link['icon']; ?> qx-margin-small-right"></em>
                  <?php
                  endif; ?>
                  <?php
                  echo $link['title']; ?>
              </a>
            </li>
          <?php
          endforeach; ?>
      </ul>
    </div>
      <div class="qx-navbar-right">
        <?php if(QuixHelperLicense::isPro()): ?>
          <div class="qx-navbar-item qx-padding-remove-right">
            <a href="index.php?option=com_quix&view=config" id="license-activation-cta"
               class="qx-visible@m qx-btn qx-btn-<?php
               echo $activated ? 'success' : 'danger qx-label-danger qx-border-remove'; ?>">
              <i class="qx-margin-small-right <?php echo $activated ? 'qxuicon-check-circle qx-margin-small-right' : 'qxuicon-lock'; ?>"></i>
                <?php echo $activatedText; ?>
            </a>
          </div>
      <?php else: ?>
            <div class="qx-navbar-item qx-padding-remove-right">
                <a href="https://www.themexpert.com/quix-pagebuilder?utm_medium=button&utm_campaign=quix-pro&utm_source=admin-panel&utm_content=upgrade-now" id="license-activation-cta" target="_blank"
                   class="qx-visible@m qx-btn qx-btn-danger qx-label-danger qx-border-remove">
                    <svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.8306 3.443C12.6449 3.16613 12.3334 3 12.0001 3C11.6667 3 11.3553 3.16613 11.1696 3.443L7.38953 9.07917L2.74781 3.85213C2.44865 3.51525 1.96117 3.42002 1.55723 3.61953C1.15329 3.81904 0.932635 4.26404 1.01833 4.70634L3.70454 18.5706C3.97784 19.9812 5.21293 21 6.64977 21H17.3504C18.7872 21 20.0223 19.9812 20.2956 18.5706L22.9818 4.70634C23.0675 4.26404 22.8469 3.81904 22.4429 3.61953C22.039 3.42002 21.5515 3.51525 21.2523 3.85213L16.6106 9.07917L12.8306 3.443Z" fill="#fff"></path> </g></svg>
			        <?php echo Text::_('COM_QUIX_UPGRADE_NOW'); ?>
                </a>
            </div>
      <?php endif; ?>

      <ul class="qx-navbar-nav" id="toolbar-settings-right">
        <li>
          <a href="javascript:void(0);"
             id="quixCacheClear"
             data-clear-cache=""
             qx-tooltip="title: Clear Builder Cache"
             class="qx-button qx-margin-small-left qx-visible@s">
            <span id="cache-status-normal" class="qxuicon-repeat"></span>
            <div id="cache-status-loading" class="qx-icon qx-spinner" qx-spinner="ratio: 0.4" style="display: none"></div>
          </a>
        </li>
        <li>
          <a class="buttons-bars" href="#" qx-tooltip="title: Menu Options"><span class="qxuicon-bars"></span></a>
          <div class="qx-navbar-dropdown" qx-dropdown="pos:bottom-right;mode:click">
            <ul class="qx-nav qx-navbar-dropdown-nav">
              <li>
                  <a href="index.php?option=com_config&view=component&component=com_quix&path=&return=<?php echo $returnUrl; ?>">
                      <span class="qxuicon-cog qx-margin-small-right"></span><?php echo Text::_('COM_QUIX_5_SETTINGS'); ?>
                  </a>
              </li>
              <li>
                <a href="index.php?option=com_plugins&view=plugins&filter[element]=seositeattributes">
                  <span class="qxuicon-crosshairs qx-margin-small-right"></span></span><?php echo Text::_('COM_QUIX_5_SEO_PLUGIN'); ?>
                </a>
              </li>
              <li>
                <a href="index.php?option=com_quix&view=integrations">
                  <span class="qxuicon-cubes qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_INTEGRATIONS"); ?>
                </a>
              </li>
              <li>
                <a href="index.php?option=com_quix&view=help"><span
                          class="qxuicon-info-circle qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_SYSTEM_INFO"); ?></a></li>
              <li class="qx-nav-divider"></li>
              <li>
                <a data-quix-ajax href="index.php?option=com_quix&task=cache.cleanImages&format=json">
                  <span class="qxuicon-trash qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_CLEAN_IMAGE_CACHE"); ?>
                </a>
              </li>
              <li>
                <a data-quix-ajax href="index.php?option=com_quix&task=cache.cleanPages&format=json">
                  <span class="qxuicon-trash qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_CLEAN_PAGE_CACHE"); ?>
                </a>
              </li>
              <li>
                <a data-quix-ajax href="index.php?option=com_quix&task=cache.cleanIcons&format=json">
                  <span class="qxuicon-repeat qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_SYNC_ICONS"); ?>
                </a>
              </li>
              <li>
                <a data-quix-ajax href="index.php?option=com_quix&task=clear_cache&step=0">
                  <span class="qxuicon-trash-alt qx-margin-small-right"></span><?php echo Text::_("COM_QUIX_5_CLEAN_LEGACY_CACHE"); ?>
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>

    </div>
  </nav>
</div>

<!-- This is the off-canvas -->
<div id="toolbar-mobile-menu" qx-offcanvas="mode: reveal; overlay: true">
  <div class="qx-offcanvas-bar qx-flex qx-flex-column">
    <ul class="qx-nav qx-nav-primary qx-nav-center qx-margin-auto-vertical">
      <li class="qx-active"><a href="index.php?option=com_quix&view=pages"><?php echo Text::_("COM_QUIX_PAGES"); ?></a></li>
      <li><a href=""><?php echo Text::_("COM_QUIX_HEADER"); ?> <span style="--_triangle-color: #f0506e" class="qx-label qx-label-danger qx-tooltip-triangle">PRO</span></a></li>
      <li><a href="#"><?php echo Text::_("COM_QUIX_FOOTER"); ?> <span style="--_triangle-color: #f0506e" class="qx-label qx-label-danger qx-tooltip-triangle">PRO</span></a></li>
      <li><a href="index.php?option=com_quix&view=collections"><span
                  class="qxuicon-star qx-margin-small-right"></span> <?php echo Text::_("COM_QUIX_MY_TEMPLATES"); ?></a></li>
    </ul>

    <button class="qx-offcanvas-close" type="button" qx-close></button>

  </div>
</div>

<div class="qx-container">
    <?php echo QuixHelperLayout::getWelcomeLayout(); ?>
</div>

<?php echo QuixHelperLayout::renderSysMessage(); ?>
<div class="qx-overlay-default qx-position-cover qx-hidden" id="admin-spinner" style="z-index: 1020;">
  <span class="qx-position-center qx-position-center"></span>
</div>

<div>
  <style>
  .loader {
    width: fit-content;
    font-weight: bold;
    font-family: monospace;
    font-size: 30px;
    background: radial-gradient(circle closest-side,#000 94%,#0000) right/calc(200% - 1em) 100%;
    animation: l24 1s infinite alternate linear;
    text-align: center;
    margin: 30% auto 0;
  }
  .loader::before {
    content: var(--loading-text, "Setting up...");
    line-height: 1em;
    color: #0000;
    background: inherit;
    background-image: radial-gradient(circle closest-side,#fff 94%,#000);
    -webkit-background-clip:text;
            background-clip:text;
  }

  @keyframes l24{
    100%{background-position: left}
  }
  </style>

    <div class="qx-overlay-default qx-position-cover qx-hidden" id="admin-cache-clean-loader" style="z-index: 1020;">
        <div class="loader" >
        </div>
    </div>
</div>
