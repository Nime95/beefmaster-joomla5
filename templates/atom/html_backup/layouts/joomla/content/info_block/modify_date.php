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
			<span class="modified">
				<span class="icon-calendar" aria-hidden="true"></span>
				<time datetime="<?php echo \Joomla\CMS\HTML\HTMLHelper::_('date', $displayData['item']->modified, 'c'); ?>" itemprop="dateModified">
					<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_LAST_UPDATED', \Joomla\CMS\HTML\HTMLHelper::_('date', $displayData['item']->modified, \Joomla\CMS\Language\Text::_('DATE_FORMAT_LC3'))); ?>
				</time>
			</span>
