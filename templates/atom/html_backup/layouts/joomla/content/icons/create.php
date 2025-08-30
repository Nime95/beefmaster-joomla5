<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$params = $displayData['params'];

// @deprecated  4.0  The legacy icon flag will be removed from this layout in 4.0
$legacy = $displayData['legacy'];

?>
<?php if ($params->get('show_icons')) : ?>
	<?php if ($legacy) : ?>
		<?php echo \Joomla\CMS\HTML\HTMLHelper::_('image', 'system/new.png', \Joomla\CMS\Language\Text::_('JNEW'), null, true); ?>
	<?php else : ?>
		<span class="icon-plus" aria-hidden="true"></span>
		<?php echo \Joomla\CMS\Language\Text::_('JNEW'); ?>
	<?php endif; ?>
<?php else : ?>
	<?php echo \Joomla\CMS\Language\Text::_('JNEW') . '&#160;'; ?>
<?php endif; ?>
