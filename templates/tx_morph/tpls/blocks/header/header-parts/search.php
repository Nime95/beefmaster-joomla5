<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>


<div class="head-search">

<?php
  // Including fallback code for the placeholder attribute in the search field.
  JHtml::_('jquery.framework');

  $document = JFactory::getDocument();
  $app = JFactory::getApplication();
  $AcTemplate = JFactory::getApplication()->getTemplate();
  $document->addStyleSheet( JURI::base(true).'/templates/'.$AcTemplate. '/css/search-component.css');
  $document->addStyleSheet( JURI::base(true).'/templates/'.$AcTemplate. '/css/search-default.css');
  $document->addScript( JURI::base(true).'/templates/'.$AcTemplate. '/js/search-classie.js');
  $document->addScript( JURI::base(true).'/templates/'.$AcTemplate. '/js/search-uisearch.js');

  ?>
  <div id="sb-search" class="sb-search expanding-search">
    <?php if($app->input->get('t3action') != 'layout') : ?>
      <form action="<?php echo JRoute::_('index.php'); ?>" method="post">
    <?php endif; ?>

      <input class="sb-search-input" placeholder="<?php echo JText::_('TX_SEARCH')?>" type="text" value="" name="searchword" id="mod-search-searchword">
      <input class="sb-search-submit" type="submit" value="">
      <span class="sb-icon-search">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 451 451" style="enable-background:new 0 0 451 451;" xml:space="preserve" width="18px" height="18px">
        <g>
        	<path d="M447.05,428l-109.6-109.6c29.4-33.8,47.2-77.9,47.2-126.1C384.65,86.2,298.35,0,192.35,0C86.25,0,0.05,86.3,0.05,192.3   s86.3,192.3,192.3,192.3c48.2,0,92.3-17.8,126.1-47.2L428.05,447c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4   C452.25,441.8,452.25,433.2,447.05,428z M26.95,192.3c0-91.2,74.2-165.3,165.3-165.3c91.2,0,165.3,74.2,165.3,165.3   s-74.1,165.4-165.3,165.4C101.15,357.7,26.95,283.5,26.95,192.3z" />
        </g>
        </svg>
      </span>

    <?php if($app->input->get('t3action') != 'layout') : ?>
      <input type="hidden" name="task" value="search" />
      <input type="hidden" name="option" value="com_search" />
      <input type="hidden" name="Itemid" value="<?php echo $this->params->get('searchmenuitemid')?>" />
    </form>
    <?php endif; ?>
  </div>


  <script>
  	new UISearch( document.getElementById( 'sb-search' ) );
  </script>

</div>
