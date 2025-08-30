<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jmedia
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

\Joomla\CMS\HTML\HTMLHelper::_('behavior.core');

$title = Text::_('About');
?>
<button type="button" data-toggle="modal" onclick="jQuery( '#aboutModal' ).modal('show');" class="btn btn-small">
	<span class="icon-checkbox-partial" aria-hidden="true"></span>
	<?php echo $title; ?>
</button>
<style type="text/css">#toolbar-about{float: right;}</style>
