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

<div class="header-area header-v11" data-sticky>
	<!-- HEADER -->
	<header id="t3-header" class="t3-header clearfix">


		<!-- LOGO -->
		<div class="logo pull-left">
			<?php include ('header-parts/brand.php'); ?>
		</div>
		<!-- //LOGO -->

		<!-- //Main Menu -->
		<div class="tx-main-menu pull-left">

			<!-- MAIN NAVIGATION -->
			<nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav pull-right">

				<div class="t3-navbar navbar-collapse collapse">
					<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />
				</div>

			</nav>
			<!-- //MAIN NAVIGATION -->

		</div>


		<div class="search-right pull-right">
			<div class="header-social hidden-xs pull-right">
				<?php include ('header-parts/social.php'); ?>
			</div>
			<div class="hidden-xs pull-right">
				<?php include ('header-parts/search.php'); ?>
			</div>

			<div class="pull-right">
				<?php $this->loadBlock ('off-canvas') ?>
			</div>
		</div>


	</header>
	<!-- //HEADER -->
</div>
