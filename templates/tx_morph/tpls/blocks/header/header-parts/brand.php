<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>


<div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
  <a href="<?php echo JUri::base() ?>" title="<?php echo strip_tags($sitename) ?>">
    <?php if($logotype == 'image'): ?>
      <img class="logo-img" src="<?php echo JUri::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
    <?php endif ?>
    <?php if($logoimgsm) : ?>
      <img class="logo-img-sm" src="<?php echo JUri::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
    <?php endif ?>
    <span><?php echo $sitename ?></span>
  </a>
  <small class="site-slogan"><?php echo $slogan ?></small>
</div>
