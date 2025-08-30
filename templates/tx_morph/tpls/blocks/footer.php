<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('footer-widget')) : ?>
<div class="footer-widget">
	<jdoc:include type="modules" name="<?php $this->_p('footer-widget') ?>" style="raw" />
</div>
<?php endif; ?>

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer <?php echo $this->getParam('fixed_footer') ? 'fixed-footer' : ' '; ?>">

	<?php if ($this->checkSpotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6')) : ?>
		<!-- FOOT NAVIGATION -->
		<div class="container">
			<?php $this->spotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6') ?>
		</div>
		<!-- //FOOT NAVIGATION -->
	<?php endif ?>

	<jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />


<?php if ($this->getParam('enable_copyright')) : ?>

	<section class="t3-copyright text-center">
		<div class="container">
			<div class="row">
				<div class="<?php echo $this->getParam('t3-rmvlogo', 1) ? 'col-md-8' : 'col-md-12' ?> copyright <?php $this->_c('footer') ?>">

				  <small>
					  <?php
					  $copyright_text = $this->params->get('copyright_text', 'Copyright &copy; 2016-2017 ThemeXpert. All Rights Reserved.');

					  if (!empty($copyright_text)) {
						  echo $copyright_text;
					  }
					  ?>
				  </small>
				</div>
				<?php if ($this->getParam('t3-rmvlogo', 1)): ?>
					<div class="col-md-4 poweredby text-hide">
						<a class="t3-logo t3-logo-color" href="http://t3-framework.org" title="<?php echo JText::_('T3_POWER_BY_TEXT') ?>"
						   target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>><?php echo JText::_('T3_POWER_BY_HTML') ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<p style="display: none;" class="text-center credit-text <?php echo ( $this->getParam('tx_credit', 1) ? 'text-muted' : 'hide' ) ?>">
	<a class="text-muted" href="https://www.themexpert.com/joomla-templates/" title="Joomla Template" target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>>Joomla Template</a> by <a href="https://www.themexpert.com/" target="_blank">ThemeXpert</a>
</p>

</footer>
<!-- //FOOTER -->


<?php if ($this->getParam('go_to_top')) : ?>
<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top" data-spy="affix" data-offset-top="300" class="back-to-top hidden-xs affix-top">
	<button class="btn btn-primary" title="Back to Top"><i class="fa fa-angle-up"></i></button>
</div>
<?php endif; ?>
