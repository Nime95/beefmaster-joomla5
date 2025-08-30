<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- META FOR IOS & HANDHELD -->
<?php if ($this->getParam('responsive', 1)): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

	<?php
	if ($this->getParam('favicon_upload')) :
		$favicon = JUri::root().$this->getParam('favicon_upload');
		JFactory::getDocument()->addFavicon($favicon);
	endif;
	?>

	<style type="text/stylesheet">
		@-webkit-viewport   { width: device-width; }
		@-moz-viewport      { width: device-width; }
		@-ms-viewport       { width: device-width; }
		@-o-viewport        { width: device-width; }
		@viewport           { width: device-width; }
	</style>
	<script type="text/javascript">
		//<![CDATA[
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement("style");
			msViewportStyle.appendChild(
				document.createTextNode("@-ms-viewport{width:auto!important}")
			);
			document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
		}
		//]]>
	</script>
<?php endif ?>

<meta name="HandheldFriendly" content="true"/>
<meta name="apple-mobile-web-app-capable" content="YES"/>
<!-- //META FOR IOS & HANDHELD -->

<?php
// SYSTEM CSS
$this->addStyleSheet(JUri::base(true) . '/templates/system/css/general.css');
?>

<?php
// T3 BASE HEAD
$this->addHead();

?>



<?php if ($this->getParam('nav_sticky', 1)) : ?>
  <script type="text/javascript">
    (function($) {

      $( document ).ready(function() {

        var heightRoof = $('.t3-sl-roof').innerHeight();
		var sticky = $('[data-sticky]');
		
        //top menu
        $(window).bind('scroll', function () {
          if ($(window).scrollTop() > heightRoof) {
            sticky.addClass('navbar-fixed-top');
          } else {
            sticky.removeClass('navbar-fixed-top');
          }
        });

      });
    })(jQuery);
  </script>
<?php endif; ?>



<?php if ($this->getParam('video_bg_enable', 1) && !empty($this->getParam('video_bg_class'))) : ?>
	<script type="text/javascript">
		(function($) {

			$( document ).ready(function() {

				//BG Video
				$(".<?php echo $this->getParam('video_bg_class') ?>").background({
					source: {
						poster: "<?php echo JUri::root().$this->getParam('video_poster', '') ?>",
						video: "https://www.youtube.com/embed/<?php echo $this->getParam('ytb_video_id', '') ?>",
					}
				});
			});
		})(jQuery);
	</script>
	<?php
		$style = '.' . $this->getParam('video_bg_class'). ' .qx-column {z-index: 1;position: relative;}';
		$this->addStyleDeclaration($style);
	?>
<?php endif; ?>




<?php
// CUSTOM CSS
if (is_file(T3_TEMPLATE_PATH . '/css/custom.css')) {
	$this->addStyleSheet(T3_TEMPLATE_URL . '/css/custom.css');
}

// CUSTOM js
if (is_file(T3_TEMPLATE_PATH . '/js/script.js')) {
	$this->addScript(T3_TEMPLATE_URL . '/js/script.js');
}
?>

<!-- Le HTML5 shim and media query for IE8 support -->
<!--[if lt IE 9]>
<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
<script type="text/javascript" src="<?php echo T3_URL ?>/js/respond.min.js"></script>
<![endif]-->

<!-- You can add Google Analytics here or use T3 Injection feature -->
