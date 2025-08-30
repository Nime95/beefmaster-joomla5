<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>
<?php $fields = $this->form->getFieldset('params'); ?>
<?php if (count($fields)) : ?>
	<fieldset id="users-profile-custom" class="qx-fieldset">
		<legend>
			<h4 class="qx-h4 qx-text-bold qx-margin-remove-bottom"><?php echo \Joomla\CMS\Language\Text::_('COM_USERS_SETTINGS_FIELDSET_LABEL'); ?></h4>
		</legend>
		<div class="qx-margin">
			<?php foreach ($fields as $field) : ?>
				<?php if (!$field->hidden) : ?>
					<div class="qx-margin-small">
						<label class="qx-text-bold"><?php echo $field->title; ?>: </label>
						<?php if (\Joomla\CMS\HTML\HTMLHelper::isRegistered('users.' . $field->id)) : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('users.' . $field->id, $field->value); ?>
						<?php elseif (\Joomla\CMS\HTML\HTMLHelper::isRegistered('users.' . $field->fieldname)) : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('users.' . $field->fieldname, $field->value); ?>
						<?php elseif (\Joomla\CMS\HTML\HTMLHelper::isRegistered('users.' . $field->type)) : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('users.' . $field->type, $field->value); ?>
						<?php else : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('users.value', $field->value); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</fieldset>
<?php endif; ?>
