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
\Joomla\CMS\HTML\HTMLHelper::register('users.spacer', array('JHtmlUsers', 'spacer'));

$fieldsets = $this->form->getFieldsets();

if (isset($fieldsets['core']))
{
	unset($fieldsets['core']);
}

if (isset($fieldsets['params']))
{
	unset($fieldsets['params']);
}

$tmp          = isset($this->data->jcfields) ? $this->data->jcfields : array();
$customFields = array();

foreach ($tmp as $customField)
{
	$customFields[$customField->name] = $customField;
}

?>
<?php foreach ($fieldsets as $group => $fieldset) : ?>
	<?php $fields = $this->form->getFieldset($group); ?>
	<?php if (count($fields)) : ?>
		<fieldset id="users-profile-custom-<?php echo $group; ?>" class="qx-fieldset users-profile-custom-<?php echo $group; ?>">
			<?php if (isset($fieldset->label) && ($legend = trim(\Joomla\CMS\Language\Text::_($fieldset->label))) !== '') : ?>
				<legend>
					<h4 class="qx-h4 qx-text-bold qx-margin-remove-bottom"><?php echo $legend; ?></h4>
				</legend>
			<?php endif; ?>
			<?php if (isset($fieldset->description) && trim($fieldset->description)) : ?>
				<p><?php echo $this->escape(\Joomla\CMS\Language\Text::_($fieldset->description)); ?></p>
			<?php endif; ?>
			<div class="qx-margin">
				<?php foreach ($fields as $field) : ?>
					<?php if (!$field->hidden && $field->type !== 'Spacer') : ?>
						<div class="qx-margin-small">
							<label class="qx-text-bold"><?php echo $field->title; ?>: </label>
							<?php if (key_exists($field->fieldname, $customFields)) : ?>
								<?php echo strlen($customFields[$field->fieldname]->value) ? $customFields[$field->fieldname]->value : \Joomla\CMS\Language\Text::_('COM_USERS_PROFILE_VALUE_NOT_FOUND'); ?>
							<?php elseif (\Joomla\CMS\HTML\HTMLHelper::isRegistered('users.' . $field->id)) : ?>
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
<?php endforeach; ?>
