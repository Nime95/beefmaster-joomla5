<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
$systemInfo = $this->getSystemInfo();
$meetRequirements = true;
$trueM = '<i class="qxuicon-check-circle qx-text-success"></i>';
$falseM = '<i class="qxuicon-times-circle qx-text-danger"></i>';
?>
<h3 class="qx-font-500"><?php echo Text::_("COM_QUIX_SYSTEM_REQUIREMENTS") ?></h3>

<div class="body">
  <table id="qx-system-info-table" class="qx-table qx-table-striped qx-table-hover qx-table-small">
    <thead>
      <tr>
        <th class="qx-padding-remove-left">Required</th>
        <th></th>
        <th>Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo Text::_("COM_QUIX_PHP_VERSION") ?></td>
        <td><?php echo version_compare($systemInfo['php_version'], '7.2.0') == -1 ? $falseM : $trueM ?></td>
        <td><?php echo $systemInfo['php_version'] ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_MEMORY_LIMIT") ?></td>
        <td><?php echo intval($systemInfo['memory_limit']) > 64 ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['memory_limit'] ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_POST_SIZE") ?></td>
        <td><?php echo intval($systemInfo['postSize']) < '5' ? $falseM : $trueM ?></td>
        <td><?php echo $systemInfo['postSize'] ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_MAX_EXECUTION") ?></td>
        <td><?php echo $systemInfo['max_execution'] < '60' ? $falseM : $trueM ?></td>
        <td><?php echo $systemInfo['max_execution'] ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_CACHE_FOLDER") ?></td>
        <td><?php echo $systemInfo['cache_writable'] ? $trueM : $falseM ?></td>
        <td><?php echo ($systemInfo['cache_writable'] ? Text::_("COM_QUIX_WRITABLE") : Text::_("COM_QUIX_UNWRITABLE")) ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_CURL_SUPPORT") ?></td>
        <td><?php echo $systemInfo['curl_support'] ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['curl_support'] ? Text::_("COM_QUIX_YES") : Text::_("COM_QUIX_NO") ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_GD_SUPPORT") ?></td>
        <td><?php echo $systemInfo['gd_info'] ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['curl_support'] ? Text::_("COM_QUIX_YES") : Text::_("COM_QUIX_NO") ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_CTYPE_SUPPORT") ?></td>
        <td><?php echo $systemInfo['ctype_support'] ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['ctype_support'] ? Text::_("COM_QUIX_YES") : Text::_("COM_QUIX_NO") ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_FILEINFO_SUPPORT") ?></td>
        <td><?php echo $systemInfo['fileinfo'] ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['fileinfo'] ? Text::_("COM_QUIX_YES") : Text::_("COM_QUIX_NO") ?></td>
      </tr>
      <tr>
        <td><?php echo Text::_("COM_QUIX_ALLOW_URL_FOPEN_SUPPORT") ?></td>
        <td><?php echo $systemInfo['allow_url_fopen'] ? $trueM : $falseM ?></td>
        <td><?php echo $systemInfo['allow_url_fopen'] ? Text::_("COM_QUIX_YES") : Text::_("COM_QUIX_NO") ?></td>
      </tr>
    </tbody>
  </table>
</div><!--body end-->

