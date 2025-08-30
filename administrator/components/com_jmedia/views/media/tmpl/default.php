<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jmedia
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

\Joomla\CMS\HTML\HTMLHelper::_('behavior.keepalive');
\Joomla\CMS\HTML\HTMLHelper::_('behavior.core');
\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');

\Joomla\CMS\HTML\HTMLHelper::_('script', 'com_jmedia/app.js', ['version' => 'auto', 'relative' => true]);
\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'com_jmedia/app.css', ['version' => 'auto', 'relative' => true]);

JMediaHelper::addMediaCommonScript();
Factory::getDocument()->addScriptDeclaration("
window.onload = window.onresize = () => {
  jQuery('.filemanagerBody').css('height', jQuery('body').height() - (jQuery('.filemanagerBody').offset().top + 80) + 'px');
};");
?>
<div class="jmedia-wrapper">
  <div id="JMediaWrapper" class="JMediaWrapper-<?php echo JMDEDIA_LICENSE == 'FREE' ? 'free' : 'pro' ?>"></div>
</div>

<?php
echo JMediaHelper::getFooter();

echo \Joomla\CMS\HTML\HTMLHelper::_(
    'bootstrap.renderModal',
    'aboutModal',
    [
        'title'  => \Joomla\CMS\Language\Text::_('JMedia Filemanager for Joomla! and Quix'),
        'footer' => 'Powered by ThemeXpert',
    ],
    $this->loadTemplate('about')
);
echo \Joomla\CMS\HTML\HTMLHelper::_(
    'bootstrap.renderModal',
    'upgradeModal',
    [
        'title'  => \Joomla\CMS\Language\Text::_('JMedia Filemanager for Joomla! and Quix'),
        'footer' => 'Powered by ThemeXpert',
    ],
    $this->loadTemplate('upgrade')
);
