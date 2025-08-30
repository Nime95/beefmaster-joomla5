<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$article = $displayData['article'];
$overlib = $displayData['overlib'];

// @deprecated  4.0  The legacy icon flag will be removed from this layout in 4.0
$legacy  = $displayData['legacy'];

$currentDate   = \Joomla\CMS\Factory::getDate()->format('Y-m-d H:i:s');
$isUnpublished = ($article->publish_up > $currentDate)
	|| ($article->publish_down < $currentDate && $article->publish_down !== \Joomla\CMS\Factory::getDbo()->getNullDate());

if ($legacy)
{
	$icon = $article->state ? 'edit.png' : 'edit_unpublished.png';

	if ($isUnpublished)
	{
		$icon = 'edit_unpublished.png';
	}
}
else
{
	$icon = $article->state ? 'edit' : 'eye-close';

	if ($isUnpublished)
	{
		$icon = 'eye-close';
	}
}

?>
<?php if ($legacy) : ?>
	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('image', 'system/' . $icon, \Joomla\CMS\Language\Text::_('JGLOBAL_EDIT'), null, true); ?>
<?php else : ?>
	<span class="hasTooltip icon-<?php echo $icon; ?> tip" title="<?php echo \Joomla\CMS\HTML\HTMLHelper::tooltipText(\Joomla\CMS\Language\Text::_('COM_CONTENT_EDIT_ITEM'), $overlib, 0, 0); ?>" qx-tooltip></span>
	<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_EDIT'); ?>
<?php endif; ?>
