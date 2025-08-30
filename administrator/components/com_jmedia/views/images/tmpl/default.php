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

?>
  <div class="jmedia-wrapper">
    <div id="JMediaWrapper"
         class="JMediaWrapper-<?php echo JMDEDIA_LICENSE == 'FREE' ? 'free' : 'pro' ?>">
    </div>
  </div>
<?php
if (JVERSION < 4) {
  Factory::getDocument()->addScript(JUri::root() . 'media/com_jmedia/js/app.js?123');
  Factory::getDocument()->addStylesheet(JUri::root() . 'media/com_jmedia/css/app.css?123');      
    JMediaHelper::addMediaModalScriptJ3();
} else {
  \Joomla\CMS\HTML\HTMLHelper::_('script', 'com_jmedia/app.js', ['version' => 'auto', 'relative' => true]);
  \Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'com_jmedia/app.css', ['version' => 'auto', 'relative' => true]);
  
  JMediaHelper::addMediaModalScriptJ4();
}
Factory::getDocument()->addScriptDeclaration("
setTimeout(() => {
        jQuery('.filemanagerBody').css('height', window.innerHeight - 100 + 'px');
}, 1000);");
