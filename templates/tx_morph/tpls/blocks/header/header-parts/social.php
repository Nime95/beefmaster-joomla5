<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>

<?php if ($this->getParam('social_enable', 1)) : ?>
<div class="social">
  <ul>
    <?php if($this->params->get('facebook')) : ?>
    <li><a href="<?php echo $this->params->get('facebook')?>" target="_blank"><i class="fa fa-facebook fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('twitter')) : ?>
    <li><a href="<?php echo $this->params->get('twitter')?>" target="_blank"><i class="fa fa-twitter fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('linkedin')) : ?>
    <li><a href="<?php echo $this->params->get('linkedin')?>" target="_blank"><i class="fa fa-linkedin fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('google-plus')) : ?>
    <li><a href="<?php echo $this->params->get('google-plus')?>" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('dribbble')) : ?>
    <li><a href="<?php echo $this->params->get('dribbble')?>" target="_blank"><i class="fa fa-dribbble fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('pinterest')) : ?>
    <li><a href="<?php echo $this->params->get('pinterest')?>" target="_blank"><i class="fa fa-pinterest fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('behance')) : ?>
    <li><a href="<?php echo $this->params->get('behance')?>" target="_blank"><i class="fa fa-behance fa-fw"></i></a></li>
    <?php endif; ?>

    <?php if($this->params->get('youtube')) : ?>
    <li><a href="<?php echo $this->params->get('youtube')?>" target="_blank"><i class="fa fa-youtube fa-fw"></i></a></li>
    <?php endif; ?>
  </ul>
</div>
<?php endif; ?>
