<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<span class="create">
		<span class="icon-calendar" aria-hidden="true"></span>
		<time datetime="<?php echo \Joomla\CMS\HTML\HTMLHelper::_('date', $displayData['item']->created, 'c'); ?>" itemprop="dateCreated">
			<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_CREATED_DATE_ON', \Joomla\CMS\HTML\HTMLHelper::_('date', $displayData['item']->created, \Joomla\CMS\Language\Text::_('DATE_FORMAT_LC3'))); ?>
		</time>
</span>
