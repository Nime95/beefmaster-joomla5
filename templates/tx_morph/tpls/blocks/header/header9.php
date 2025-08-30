<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get params
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

?>

<div class="header-area header-v9">
	<!-- HEADER -->
	<header id="t3-header" class="t3-header">
		<div class="container">
			<div class="row">

				<!-- LOGO -->
				<div class="col-sm-4 col-xs-6">
					<?php include ('header-parts/brand.php'); ?>
				</div>

				<div class="col-sm-8 col-xs-6">
					<div class="module-position">
						<jdoc:include type="modules" name="header-top-right" />
					</div>

					<div class="pull-right">
						<?php $this->loadBlock ('off-canvas') ?>
					</div>

				</div>

			</div>
		</div>
	</header>
	<!-- //HEADER -->


	<!-- MAIN NAVIGATION -->
	<nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav" data-sticky>
		<div class="container">

			<div class="clearfix">
				<div class="t3-navbar navbar-collapse collapse pull-left">
					<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />
				</div>

				<?php if ($this->getParam('addon_offcanvas_enable')) : ?>
					<div class="pull-right">
						<?php $this->loadBlock ('off-canvas') ?>
						<?php include ('header-parts/search.php'); ?>
					</div>
				<?php endif ?>
			</div>

		</div>
	</nav>
	<!-- //MAIN NAVIGATION -->
</div>
