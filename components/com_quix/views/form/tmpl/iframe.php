<?php

/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    1.8.0
 */

// No direct access

use QuixNxt\Utils\Asset;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;
// Load jQuery
\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
$version = QuixAppHelper::getQuixMediaVersion();
Factory::getDocument()->addStyleSheet(Asset::getAssetUrl('/css/quix-elements.css'));
Factory::getDocument()->addStyleSheet(Asset::getAssetUrl('/css/qxi.css'));
Factory::getDocument()->addStyleSheet(Asset::getAssetUrl('/css/qxicon.css'));
Factory::getDocument()->addStyleSheet(Asset::getAssetUrl('/css/quix-core.css'));
Factory::getDocument()->addStyleSheet(Asset::getAssetUrl('/css/quix-builder.css'));
?>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://ajax.googleapis.com">

<div class="qx-fb-frame">

  <div class="app-mount qx quix">
    <div id="qx-fb-mount"></div>
  </div>
</div>

<!--iframe inside assets fix-->
<script>
  var quix = <?php echo json_encode(['url' => QUIXNXT_SITE_URL, 'version' => QUIXNXT_VERSION]) ?>;
</script>
<script src="<?php echo Asset::getAssetUrl('/js/iframe.js'); ?>" type="text/javascript"></script>
<script src="<?php echo Asset::getAssetUrl('/js/quix.js'); ?>" type="text/javascript"></script>
<script src="<?php echo Asset::getAssetUrl('/js/qxkit.js'); ?>" type="text/javascript"></script>
<?php if (!QUIX_GDPR_COMPLIANCE): ?>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript" defer></script>
<?php endif; ?>



<style>
    @font-face {
        font-family: 'qxi';
        font-display: swap;
        src: url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxi.ttf?cdywht') format('truetype'),
        url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxi.woff?cdywht') format('woff');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'qxuicon';
        font-display: swap;
        src:
            url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.ttf?a4jeee') format('truetype'),
            url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.woff?a4jeee') format('woff'),
            url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.svg?a4jeee#qxicon') format('svg');
        font-weight: normal;
        font-style: normal;
    }
</style>
