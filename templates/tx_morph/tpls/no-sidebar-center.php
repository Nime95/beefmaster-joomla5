<?php
/**
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org
 *------------------------------------------------------------------------------
 */


defined('_JEXEC') or die;
$header_transparent = ($this->params->get('header_transparent') ? ' header-transparent' : '');
// $box_layout = ($this->params->get('box_layout') ? ' boxed' : '');
$box_layout = ($this->params->get('box_layout'));
$box_layout_img = $this->params->get('box_layout_img', '');
$box_layout_bg_color = $this->params->get('box_layout_bg_color', '');
$box_image_or_color = $this->params->get('box_image_or_color', '');
$box_layout_width = $this->params->get('box_layout_width', '');

if (!empty($box_layout) && !empty($box_layout_img) && ($box_image_or_color == 'bg_img')) {
    $style = ' style="background-image: url(' . T3_TEMPLATE_URL . '/images/switcher/bg/' . $box_layout_img . ');"';
} elseif (!empty($box_layout) && !empty($box_layout_bg_color) && ($box_image_or_color == 'bg_color')) {
    $style = ' style="background-color: ' . $box_layout_bg_color . ' ;"';
} else {
    $style = '';
}

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
      class='<jdoc:include type="pageclass" /> <?php echo $header_transparent; ?>'>

<head>
    <jdoc:include type="head"/>
    <?php $this->loadBlock('head') ?>

    <?php if ($box_layout && $box_layout_width) : ?>
        <style type="text/css">
            .boxed .t3-wrapper,
            .boxed .navbar-fixed-top, .boxed .navbar-fixed-bottom, .fixed-footer {
                width: <?php echo $box_layout_width; ?> !important;
            }

        </style>
    <?php endif; ?>

</head>

<body<?php echo $style; ?><?php echo($box_layout ? ' class="boxed"' : ''); ?>>

<?php if ($this->getParam('enable_preloader')) : ?>
    <div class="preloader-box">
        <div class="preloader4"></div>
    </div><!-- /.preloader-box -->
<?php endif; ?>

<div class="t3-wrapper"> <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->
    <?php $this->loadBlock('roof') ?>

    <?php $this->loadBlock('header') ?>

    <?php $this->loadBlock('spotlight-1') ?>

    <?php $this->loadBlock('mainbody/no-sidebar-center') ?>

    <?php $this->loadBlock('spotlight-2') ?>

    <?php $this->loadBlock('navhelper') ?>

    <?php $this->loadBlock('footer') ?>

</div>

</body>

</html>
