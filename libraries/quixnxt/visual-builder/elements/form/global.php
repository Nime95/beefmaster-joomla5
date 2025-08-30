<?php
use Joomla\CMS\Factory;

if (QuixAppHelper::checkQuixIsBuilderMode()) {
    return;
}

if (!defined('QX_ELEMENT_FORM')) {
    Factory::getApplication()->allowCache(false);
    define('QX_ELEMENT_FORM', true);
    \Joomla\CMS\HTML\HTMLHelper::_('script', 'system/core.js', false, true);

    $_SESSION['quix_form_captcha'] = [
        'first_number' => rand(1, 10),
        'second_number' => rand(1, 10)
    ];
}
