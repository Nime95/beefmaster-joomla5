<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php
	if (!$this->getParam('addon_offcanvas_enable')) return ;

  $offPos = "";
  if ($this->getParam('addon_offcanvas_pos') === "0") {
    $offPos = "left";
  } else {
    $offPos = "right";
  }
?>

<button class="off-canvas-toggle <?php $this->_c('off-canvas') ?>" type="button" data-pos="<?php echo $offPos; ?>" data-nav="#t3-off-canvas" data-effect="<?php echo $this->getParam('addon_offcanvas_effect', 'off-canvas-effect-4') ?>">

  <svg class="icon" height="20" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M231.285109 231.287667h701.787484c7.750521 0 14.032598 6.285147 14.032598 14.032598v28.074405c0 7.753591-6.282077 14.032598-14.032598 14.032598H231.285109c-7.750521 0-14.035668-6.28003-14.035668-14.032598V245.320265c0-7.747451 6.285147-14.032598 14.035668-14.032598zM259.354398 483.926618h673.719218c7.750521 0 14.032598 6.285147 14.032598 14.038737v28.068266c0 7.753591-6.282077 14.038738-14.032598 14.038738H259.354398c-7.750521 0-14.032598-6.285147-14.032598-14.038738v-28.068266c-0.001023-7.753591 6.282077-14.038738 14.032598-14.038737zM90.925361 736.572732h842.147232c7.750521 0 14.032598 6.282077 14.032598 14.035668v28.071335c0 7.750521-6.282077 14.032598-14.032598 14.032598H90.925361c-7.750521 0-14.032598-6.282077-14.032598-14.032598V750.6084c0-7.753591 6.282077-14.035668 14.032598-14.035668z" fill="#231815" /></svg>

</button>

<!-- OFF-CANVAS SIDEBAR -->
<div id="t3-off-canvas" class="t3-off-canvas <?php $this->_c('off-canvas') ?>">

  <!-- <div class="t3-off-canvas-header"> -->
    <!--
    <h2 class="t3-off-canvas-header-title"><?php //echo JText::_('TPL_SIDE_BAR') ?></h2>
    -->
  <!-- </div> -->

  <div class="t3-off-canvas-body">
    <a href="javascript:void(0);" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;</a>
    <jdoc:include type="modules" name="<?php $this->_p('off-canvas') ?>" style="T3Xhtml" />
  </div>

</div>
<!-- //OFF-CANVAS SIDEBAR -->
