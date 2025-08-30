<?php
/**
 * @package     Quix
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$activated = QuixHelper::isProActivated();
$text = \Joomla\CMS\Language\Text::_('COM_QUIX_TOOLBAR_ACTIVATION');
if($activated){
  $text = \Joomla\CMS\Language\Text::_('COM_QUIX_TOOLBAR_ACTIVATION_DONE');
}
?>
<button type="button"
        data-target="#moduleEditModal"
        class="quixSettings btn btn-small hasTooltip <?php echo ($activated ? 'activated btn-success' : '')?>"
        title="<?php echo $text; ?>"
        data-toggle="modal"
        id="mySettings">
  <?php if($activated): ?>
    <i class="icon-ok" style="margin-right: 5px;"></i> <?php echo Joomla\CMS\Language\Text::_("COM_QUIX_TOOLBAR_ACTIVATION_DONE") ?>
  <?php else: ?>
    <i class="icon-lock" style="margin-right: 5px;"></i> Unverified License
  <?php endif; ?>
</button>

