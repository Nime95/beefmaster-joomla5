<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mainbody 1 columns, content only
 */
use Joomla\CMS\Factory;
$input = Factory::getApplication()->input;
$container = ( $input->getCmd('option') === 'com_quix' ) ? '' : 'container';
?>

<div id="t3-mainbody" class="t3-mainbody">
	<div class="<?php echo $container;?>">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<!-- MAIN CONTENT -->
				<div id="t3-content" class="t3-content">
					<?php if($this->hasMessage()) : ?>
						<jdoc:include type="message" />
					<?php endif ?>
					<jdoc:include type="component" />
				</div>
				<!-- //MAIN CONTENT -->
			</div>
		</div>
	</div>
</div>
