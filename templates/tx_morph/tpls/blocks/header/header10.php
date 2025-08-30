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

<div class="header-area header-v10">
	<!-- HEADER -->
	<header id="t3-header" class="t3-header" data-sticky>
		<div class="container">
			<div class="row">

				<div class="col-xs-6">
					<?php include ('header-parts/brand.php'); ?>
				</div>

				<div class="col-xs-6">
					<div class="pull-right">
						<?php $this->loadBlock ('off-canvas') ?>
					</div>
				</div>

			</div>
		</div>
	</header>
	<!-- //HEADER -->
</div>
