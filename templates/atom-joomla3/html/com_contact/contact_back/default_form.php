<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

?>
<div class="contact-form">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal well qx-form-stacked qx-form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<?php if ($fieldset->name === 'captcha' && !$this->captchaEnabled) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<?php $fields = $this->form->getFieldset($fieldset->name); ?>
			<?php if (count($fields)) : ?>
				<fieldset class="qx-fieldset">
					<?php if (isset($fieldset->label) && ($legend = trim(JText::_($fieldset->label))) !== '') : ?>
						<legend><?php echo $legend; ?></legend>
					<?php endif; ?>
					<div class="qx-margin">
						<label id="jform_contact_name-lbl" for="jform_contact_name" class="qx-form-label"><?php echo JText::_('COM_CONTACT_CONTACT_EMAIL_NAME_LABEL'); ?><span class="star">&nbsp;*</span></label>
						<div class="qx-form-controls">
							<input type="text" name="jform[contact_name]" id="jform_contact_name" value="" class="required invalid qx-input" size="30" required="required" aria-required="true" aria-invalid="true">
						</div>
					</div>						
					<div class="qx-margin">
						<label id="jform_contact_email-lbl" for="jform_contact_email" class="qx-form-label"><?php echo JText::_('COM_CONTACT_EMAIL_LABEL'); ?><span class="star">&nbsp;*</span></label>
						<div class="qx-form-controls">
							<input type="email" name="jform[contact_email]" class="validate-email required qx-input" id="jform_contact_email" value="" size="30" autocomplete="email" required="required" aria-required="true">
						</div>
					</div>						
					<div class="qx-margin">
						<label id="jform_contact_emailmsg-lbl" for="jform_contact_emailmsg" class="qx-form-label"><?php echo JText::_('COM_CONTACT_CONTACT_MESSAGE_SUBJECT_LABEL'); ?><span class="star">&nbsp;*</span></label>
						<div class="qx-form-controls">
							<input type="text" name="jform[contact_subject]" id="jform_contact_emailmsg" value="" class="required qx-input" size="60" required="required" aria-required="true">	
						</div>
					</div>						
					<div class="qx-margin">
						<label id="jform_contact_message-lbl" for="jform_contact_message" class="qx-form-label"><?php echo JText::_('COM_CONTACT_CONTACT_ENTER_MESSAGE_LABEL'); ?><span class="star">&nbsp;*</span></label>
						<div class="qx-form-controls">
							<textarea name="jform[contact_message]" id="jform_contact_message" cols="50" rows="10" class="required qx-textarea" required="required" aria-required="true"></textarea>		
						</div>
					</div>						
					<?php //foreach ($fields as $field) : ?>
						<?php //echo $field->renderField(); ?>					
					<?php //endforeach; ?>
				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>
		<div class="control-group">
			<label class="qx-form-label"></label>
			<div class="qx-form-controls">
				<button class="qx-button qx-button-primary validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
				<input type="hidden" name="option" value="com_contact" />
				<input type="hidden" name="task" value="contact.submit" />
				<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
				<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
</div>
