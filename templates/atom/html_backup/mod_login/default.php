<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Component\ComponentHelper;

JLoader::register('UsersHelperRoute', JPATH_SITE . '/components/com_users/helpers/route.php');

\Joomla\CMS\HTML\HTMLHelper::_('behavior.keepalive');
\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip');

?>
<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="qx-form-stacked">
	<?php if ($params->get('pretext')) : ?>
		<div class="qx-margin">
			<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		<div id="form-login-username" class="qx-margin">
			<div class="qx-form-controls">
				<?php if (!$params->get('usetext', 0)) : ?>
					<div class="input-prepend">
						<input id="modlgn-username" type="text" name="username" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>" />
					</div>
				<?php else : ?>
					<input id="modlgn-username" type="text" name="username" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_VALUE_USERNAME'); ?>" />
				<?php endif; ?>
			</div>
		</div>
		<div id="form-login-password" class="qx-margin">
			<div class="qx-form-controls">
				<?php if (!$params->get('usetext', 0)) : ?>
					<div class="input-prepend">
						<input id="modlgn-passwd" type="password" name="password" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_PASSWORD'); ?>" />
					</div>
				<?php else : ?>
					<input id="modlgn-passwd" type="password" name="password" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_PASSWORD'); ?>" />
				<?php endif; ?>
			</div>
		</div>
		<?php if (count($twoFactorMethods) > 1) : ?>
		<div id="form-login-secretkey" class="qx-margin">
			<div class="qx-form-controls">
				<?php if (!$params->get('usetext', 0)) : ?>
					<div class="input-prepend input-append">
						<span class="add-on">
							<span class="icon-star hasTooltip" qx-tooltip="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY'); ?>">
							</span>
								<label for="modlgn-secretkey" class="element-invisible"><?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY'); ?>
							</label>
						</span>
						<input id="modlgn-secretkey" autocomplete="one-time-code" type="text" name="secretkey" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY'); ?>" />
						<span class="btn width-auto hasTooltip" qx-tooltip="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="icon-help"></span>
						</span>
				</div>
				<?php else : ?>
					<label for="modlgn-secretkey"><?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY'); ?></label>
					<input id="modlgn-secretkey" autocomplete="one-time-code" type="text" name="secretkey" class="qx-input" tabindex="0" size="18" placeholder="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY'); ?>" />
					<span class="btn width-auto hasTooltip" title="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_SECRETKEY_HELP'); ?>">
						<span class="icon-help"></span>
					</span>
				<?php endif; ?>

			</div>
		</div>
		<?php endif; ?>
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="qx-margin checkbox">
		<input id="modlgn-remember" type="checkbox" name="remember" class="qx-checkbox" value="yes"/> <label for="modlgn-remember" class="control-label"><?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_REMEMBER_ME'); ?></label>
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="qx-margin">
			<div class="qx-form-controls">
				<button type="submit" tabindex="0" name="Submit" class="qx-button qx-button-primary login-button"><?php echo \Joomla\CMS\Language\Text::_('JLOGIN'); ?></button>
			</div>
		</div>
		<?php
			$usersConfig = ComponentHelper::getParams('com_users'); ?>
			<ul class="qx-list qx-link-text">
			<?php if ($usersConfig->get('allowUserRegistration')) : ?>
				<li>
					<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_users&view=registration'); ?>">
					<?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
				</li>
			<?php endif; ?>
				<li>
					<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_users&view=remind'); ?>">
					<?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
				</li>
				<li>
					<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo \Joomla\CMS\Language\Text::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
			</ul>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
