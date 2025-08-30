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

<div class="header-area header-v7 header-v8">
	<!-- HEADER -->
	<header id="t3-header" class="t3-header">
		<div class="container">
			<div class="row">

				<div class="col-xs-4 hidden-xs">
					<div class="header-social">
						<?php include ('header-parts/social.php'); ?>
					</div>
				</div>

				<div class="col-xs-4 text-center">
					<?php include ('header-parts/brand.php'); ?>
				</div>

				<div class="col-xs-8 col-sm-4">
					<div class="pull-right">
						<div class="search-right">
							<?php include ('header-parts/search.php'); ?>
						</div>


						<div class="pull-right">
							<?php $this->loadBlock ('off-canvas') ?>
						</div>

						<div class="top-right-module">
							<jdoc:include type="modules" name="header-top-right" />
						</div>
					</div>
				</div>

			</div>
		</div>
	</header>
	<!-- //HEADER -->

  <!-- MAIN NAVIGATION -->
  <nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav" data-sticky>
  	<div class="container">

  		<!-- Brand and toggle get grouped for better mobile display -->
  		<div class="navbar-header">

  			<?php if ($this->getParam('navigation_collapse_enable', 1) && $this->getParam('responsive', 1)) : ?>
  				<?php $this->addScript(T3_URL.'/js/nav-collapse.js'); ?>
  				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".t3-navbar-collapse">
  					<svg class="icon" height="20" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M231.285109 231.287667h701.787484c7.750521 0 14.032598 6.285147 14.032598 14.032598v28.074405c0 7.753591-6.282077 14.032598-14.032598 14.032598H231.285109c-7.750521 0-14.035668-6.28003-14.035668-14.032598V245.320265c0-7.747451 6.285147-14.032598 14.035668-14.032598zM259.354398 483.926618h673.719218c7.750521 0 14.032598 6.285147 14.032598 14.038737v28.068266c0 7.753591-6.282077 14.038738-14.032598 14.038738H259.354398c-7.750521 0-14.032598-6.285147-14.032598-14.038738v-28.068266c-0.001023-7.753591 6.282077-14.038738 14.032598-14.038737zM90.925361 736.572732h842.147232c7.750521 0 14.032598 6.282077 14.032598 14.035668v28.071335c0 7.750521-6.282077 14.032598-14.032598 14.032598H90.925361c-7.750521 0-14.032598-6.282077-14.032598-14.032598V750.6084c0-7.753591 6.282077-14.035668 14.032598-14.035668z" /></svg>
  				</button>
  			<?php endif ?>

  		</div>

  		<?php if ($this->getParam('navigation_collapse_enable')) : ?>
  			<div class="t3-navbar-collapse navbar-collapse collapse"></div>
  		<?php endif ?>

  		<div class="t3-navbar navbar-collapse collapse">
  			<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />
  		</div>

  	</div>
  </nav>
  <!-- //MAIN NAVIGATION -->
</div>
