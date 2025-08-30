<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$item = $displayData;

if ($item->language === '*')
{
	echo \Joomla\CMS\Language\Text::alt('JALL', 'language');
}
elseif ($item->language_image)
{
	echo \Joomla\CMS\HTML\HTMLHelper::_('image', 'mod_languages/' . $item->language_image . '.gif', '', null, true) . '&nbsp;' . htmlspecialchars($item->language_title, ENT_COMPAT, 'UTF-8');
}
elseif ($item->language_title)
{
	echo htmlspecialchars($item->language_title, ENT_COMPAT, 'UTF-8');
}
else
{
	echo \Joomla\CMS\Language\Text::_('JUNDEFINED');
}
