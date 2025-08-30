<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory;

\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$user          = Factory::getUser();
$userId        = $user->get('id');
$listOrder     = $this->state->get('list.ordering');
$listDirection = $this->state->get('list.direction');
$canOrder      = $user->authorise('core.edit.state', 'com_quix');
$saveOrder     = $listOrder == 'a.`ordering`';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_quix&task=collections.saveOrderAjax&tmpl=component';
    \Joomla\CMS\HTML\HTMLHelper::_('sortablelist.sortable', 'pageList', 'adminForm', strtolower($listDirection), $saveOrderingUrl);
}

$sortFields        = $this->getSortFields();
$filter_collection = $this->app->getUserStateFromRequest('com_quix.themes.filter.collection', 'filter_collection', 'all', 'string');
$filter_collection_arr = [
  "all" => JText::_('COM_QUIX_ALL'),
  "header" => JText::_('COM_QUIX_HEADER'),
  "footer" => JText::_('COM_QUIX_FOOTER'),
  "template" => JText::_('COM_QUIX_TEMPLATE'),
];

$layout = new JLayoutFile('blocks.toolbar');
echo $layout->render(['active' => 'collections.'.$filter_collection]);
?>
<div class="quix qx-container qx-text-small">
  <form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=collections'); ?>" method="post" name="adminForm"
        id="adminForm">
      <div class="qx-grid qx-grid-small" qx-grid="">
          <div class="qx-width-expand@m qx-first-column">
              <div class="card qx-padding-medium qx-background-white">
                  <div class="qx-margin">
                      <h3 class="qx-h3 qx-margin-small">
                          <?php echo ucfirst($filter_collection_arr[$filter_collection]);?>
                      </h3>
                  </div>
                  <!--pages list toolbar with action button and filter search-->
                  <div class="qx-margin-small-bottom">
                      <div id="page-filter" class="qx-margin-small-bottom">
                          <div class="qx-gird-small qx-grid" qx-grid>
                              <!-- search -->
                              <div class="qx-width-1-3@s qx-flex">

                                  <a href="#" class="qx-button qx-button-primary" qx-toggle="target: #new-template">
                                      <span class="qxuicon-plus qx-margin-small-right"></span> <?php echo JText::_("COM_QUIX_NEW") ?> <?php echo ucfirst($filter_collection_arr[$filter_collection === "all" ? "template" : $filter_collection]);?>
                                  </a>
				                  <?php if ($this->canDo->get('core.edit.state')): ?>
                                      <a
                                              href="javascript::void(0);"
                                              id="toolbar-trash"
                                              onclick="if (document.adminForm.boxchecked.value == 0) { alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')); } else { Joomla.submitbutton('collections.trash'); }"
                                              class="qx-button qx-button-danger qx-margin-small-left qx-hidden"
                                              qx-tooltip="title: Trash your item"
                                      >
                                          <span class="qxuicon-trash"></span>
                                      </a>
				                  <?php endif; ?>

				                  <?php if ($this->state->get('filter.state') === '-2' && $this->canDo->get('core.delete')): ?>
                                      <a
                                              href="javascript::void(0);"
                                              id="toolbar-remove"
                                              onclick="if (document.adminForm.boxchecked.value == 0) { alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')); } else { Joomla.submitbutton('collections.delete'); }"
                                              class="qx-button qx-button-danger qx-margin-small-left qx-hidden"
                                              qx-tooltip="title: Remove your item permanently."
                                      >
                                          <span class="qxuicon-trash-alt"></span>
                                      </a>
				                  <?php endif; ?>
                              </div>
                              <!-- Filter and item limit -->
                              <div class="qx-width-expand@s qx-flex qx-flex-right">
                                  <div class="qx-visibel@s">
                                      <input class="qx-input" type="text" name="filter_search" id="filter_search"
                                             style="border-radius: 6px 0 0 6px; border: 1px solid #80808036; border-right: none"
                                             placeholder="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER'); ?>"
                                             value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                                             title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER'); ?>" />
                                  </div>

                                  <div class="qx-visible@s">
                                      <button class="qx-button qx-button-default qx-margin-small-right" type="submit"
                                              style="border-radius: 0 6px 6px 0; padding-inline: 0.7rem; border: 1px solid #80808036; border-left: none"
                                              title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_SUBMIT'); ?>">
                                          <span class="qxuicon-search"></span>
                                      </button>
                                  </div>

                                  <div class="qx-visible@s">
                                      <label for="sortTable" class="element-invisible"><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_PUBLISHED'); ?></label>
                                      <select name="filter_published" id="filter_published" class="qx-select qx-border-rounded" onchange="this.form.submit()">
                                          <option value=""><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_PUBLISHED'); ?></option>
						                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_(
							                  'select.options',
							                  \Joomla\CMS\HTML\HTMLHelper::_('jgrid.publishedOptions'),
							                  'value',
							                  'text',
							                  $this->state->get('filter.state'),
							                  true
						                  ); ?>
                                      </select>
                                  </div>

                                  <div class="qx-visible@s qx-margin-small-left">
                                      <label for="limit" class="element-invisible">
						                  <?php echo \Joomla\CMS\Language\Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
                                      </label>
                                      <select name="limit" id="limit" class="qx-select qx-border-rounded" onchange="Joomla.submitform();">
                                          <option value=""><?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_DISPLAY_NUM'); ?></option>
						                  <?php
						                  $limits = array();
						                  for ($i = 5; $i <= 30; $i += 5) {
							                  $limits[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', "$i");
						                  }
						                  $limits[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', '50', \Joomla\CMS\Language\Text::_('J50'));
						                  $limits[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', '100', \Joomla\CMS\Language\Text::_('J100'));
						                  $limits[] = \Joomla\CMS\HTML\HTMLHelper::_('select.option', '0', \Joomla\CMS\Language\Text::_('JALL'));
						                  echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', $limits, 'value', 'text', $this->state->get('list.limit'), true);
						                  ?>
                                      </select>
                                  </div>
                              </div>
                          </div>
                      </div>

	                  <?php if (count($this->items)) : ?>
                          <table id="qx-table" class="qx-table">
                              <thead>
                              <tr>
				                  <?php if (isset($this->items[0]->ordering)) : ?>
                                      <th width="1%" class="nowrap center qx-visible@s">
						                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.`ordering`', $listDirection, $listOrder, null, 'asc',
							                  'JGRID_HEADING_ORDERING'); ?>
                                      </th>
				                  <?php endif; ?>
                                  <th width="1%" class="qx-visible@s">
                                      <input type="checkbox" name="checkall-toggle" value="" title="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_CHECK_ALL'); ?>"
                                             onclick="Joomla.checkAll(this)" />
                                  </th>

                                  <th width="1%"></th>

                                  <th width="40%">
					                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_QUIX_PAGES_TITLE', 'a.`title`', $listDirection, $listOrder); ?>
                                  </th>

                                  <th></th>

                                  <th width="13%" class='left qx-text-center'>
					                  <?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_COLLECTION_SHORTCODE'); ?>
                                  </th>

                                  <th width="10%" class='left qx-text-center'>
					                  <?php echo \Joomla\CMS\Language\Text::_('COM_QUIX_COLLECTION_TYPE'); ?>
                                  </th>

				                  <?php if (isset($this->items[0]->id)) : ?>
                                      <th width="1%" class="nowrap qx-text-center center qx-visible@s">
						                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.`id`', $listDirection, $listOrder); ?>
                                      </th>
				                  <?php endif; ?>
                                <th width="1%" style="padding-inline: 0 !important;"></th>
                              </tr>
                              </thead>

                              <tbody>
			                  <?php foreach ($this->items as $i => $item) :
				                  $ordering = ($listOrder == 'a.ordering');
				                  $canCreate = $user->authorise('core.create', 'com_quix');
				                  $canEdit = $user->authorise('core.edit', 'com_quix');
				                  $canCheckin = $user->authorise('core.manage', 'com_quix');
				                  $canChange = $user->authorise('core.edit.state', 'com_quix');
				                  ?>
                                  <tr class="qx-background-default row<?php echo $i % 2; ?>">
					                  <?php if (isset($this->items[0]->ordering)) : ?>
                                          <td class="order nowrap center qx-visible@s">
							                  <?php
							                  if ($canChange) :
								                  $disableClassName = '';
								                  $disabledLabel = '';
								                  if ( ! $saveOrder) :
									                  $disabledLabel    = \Joomla\CMS\Language\Text::_('JORDERINGDISABLED');
									                  $disableClassName = 'inactive tip-top';
								                  endif;
								                  switch ($item->state) {
									                  case 1:
										                  $status_text  = 'P';
										                  $status_class = 'primary';
										                  break;
									                  case 2:
										                  $status_text  = 'A';
										                  $status_class = 'secondary';
										                  break;
									                  default:
										                  $status_text  = 'U';
										                  $status_class = 'danger';
										                  break;
								                  }
								                  ?>
                                                  <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
                                                        title="<?php echo $disabledLabel ?>">
              <i class="icon-menu"></i>
            </span>
                                                  <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>"
                                                         class="width-20 text-area-order " />
							                  <?php else : ?>
                                                  <span class="sortable-handler inactive">
              <i class="icon-menu"></i>
            </span>
							                  <?php endif; ?>
                                          </td>
					                  <?php endif; ?>
                                      <td class="qx-visible@s">
						                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
                                      </td>
					                  <?php if (isset($this->items[0]->state)) : ?>
						                  <?php $item->state = (int) $item->state; ?>
                                          <td>

                                              <div class="qx-button-group">
                                                  <a
                                                          class="qx-button qx-button-small qx-button-<?php echo $status_class ?>"
                                                          qx-tooltip="title: Click to <?php echo $item->state === 1 ? 'Unpublish' : 'Publish' ?>"
                                                          href="javascript:void(0);"
                                                          onclick="return Joomla.listItemTask('cb<?php echo $i; ?>','collections.<?php echo $item->state === 1 ? 'unpublish' : 'publish' ?>')">
									                  <?php echo $status_text; ?>
                                                  </a>
                                              </div>
                                          </td>
					                  <?php endif; ?>
                                      <td>
						                  <?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
							                  <?php echo \Joomla\CMS\HTML\HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'collections.', $canCheckin); ?>
						                  <?php endif; ?>
						                  <?php if ($canEdit) : ?>
							                  <?php
							                  if ($item->builder == 'classic') {
								                  $link = 'index.php?option=com_quix&task=collection.edit&id='.(int) $item->id;
							                  } else {
								                  $link = JUri::root().'index.php?option=com_quix&task=collection.edit&id='.(int) $item->id.'&quixlogin=true';
							                  } ?>
                                              <a <?php echo($item->builder == 'frontend' ? 'target="_blank"' : ''); ?> href="<?php echo $link ?>">
								                  <?php echo $this->escape($item->title); ?>
                                              </a>
						                  <?php else : ?>
							                  <?php echo $this->escape($item->title); ?>
						                  <?php endif; ?>

						                  <?php echo($item->builder == 'classic' ? '<span class="qx-label">Classic</span>' : ''); ?>

						                  <?php echo(($item->type != 'layout' and $item->type != 'section') ? '<span class="qx-label qx-label-'.$item->type.'">'.ucfirst($item->type).'</span>' : ''); ?>
						                  <?php if ($item->builder !== 'classic'): ?>
                                              <div class="qx-text-meta"><small>Version: <?php echo $item->builder_version; ?>
                                                      <!--                            <a-->
                                                      <!--                                href="index.php?option=com_quix&task=config.reverseVersion&type=collections&id=--><?php //echo $item->id.'&'.JSession::getFormToken().'=1'; ?><!--"-->
                                                      <!--                                qx-tooltip="Fix wrong version number"><i-->
                                                      <!--                                  class="qxuicon-first-aid"></i></a>-->
                                                  </small></div>
						                  <?php endif; ?>
                                      </td>
                                      <td>
                                          <a class="qx-button qx-button-text" target="_blank"
                                             href="<?php echo JUri::root().'index.php?option=com_quix&view=collection&&id='.$item->id; ?>">
                                              <i class="qxuicon-eye qx-margin-small-right"></i>Preview
                                          </a>
                                      </td>

                                      <td class="qx-text-center">
            <span class="qx-label">
              [quix id='<?php echo $item->id; ?>']
            </span>
                                      </td>

                                      <td class="qx-text-center">
						                  <?php echo ucfirst($item->type); ?>
                                      </td>

					                  <?php if (isset($this->items[0]->id)) : ?>
                                        <td style="position: relative;" class="center qx-text-center qx-visible@s">
                                          <div style="min-width: 3rem"><?php echo (int) $item->id; ?></div>
                                        </td>
					                  <?php endif; ?>
                                    <td style="padding-inline: 0!important">
                                        <div class="qx-inline">
                                              <button class="qx-button qx-button-default qx-button-small" type="button"><span
                                                          class="qxuicon-ellipsis-v"></span></button>
                                              <div class="qx-dropdown" qx-dropdown="mode:click">
                                                  <ul class="qx-nav qx-dropdown-nav">
                                                      <li>
                                                          <a href="javascript://"
                                                              onclick="Joomla.listItemTask('cb<?php echo $i; ?>', 'collections.duplicate')">
                                                              <span class="qxuicon-copy qx-margin-small-right" aria-hidden="true"></span>Duplicate Item
                                                          </a>
                                                      </li>
                                <?php if ($item->state != 2): ?>
                                                          <li>
                                                              <a href="javascript://"
                                                                  onclick="Joomla.listItemTask('cb<?php echo $i; ?>', 'collections.archive')">
                                                                  <span class="qxuicon-archive qx-margin-small-right" aria-hidden="true"></span>Archive Item
                                                              </a>
                                                          </li>
                                <?php endif; ?>
                                                  </ul>
                                              </div>
                                        </div>
                                    </td>
                                  </tr>
			                  <?php endforeach; ?>
                              </tbody>
                          </table>
		                  <?php echo $this->pagination->getListFooter(); ?>

	                  <?php else : ?>
                          <div class="qx-padding qx-text-center">
                              <h3 class="qx-margin-remove-top qx-card-title qx-font-500"><?php echo JText::sprintf('COM_QUIX_NO_ITEM_FOUND', $filter_collection_arr[$filter_collection === "all" ? "template" : $filter_collection]) ?></h3>
                              <p class="qx-subtitle-text qx-margin-remove-bottom qx-text-lowercase"><?php echo JText::sprintf('COM_QUIX_CREATE_FIRST_ITEM_DESC', $filter_collection_arr[$filter_collection === "all" ? "template" : $filter_collection]) ?></p>
                          </div>
	                  <?php endif; ?>
                  </div>
              </div>
          </div>
          <div style="padding-left: 20px" class="qx-width-1-4@m">
		      <?php
		      $activated     = QuixHelperLicense::isProActivated();
		      if(!$activated):
			      ?>
                  <div class="qx-card qx-padding-medium qx-margin qx-background-white qx-border-remove">
                      <div class="qx-relative">
                          <div class="qx-text-center">
                              <img class="qx-go-pro-img" src="<?php echo QuixAppHelper::getQuixMediaUrl().'/images/go-pro.png' ?>" alt="Pro image"/>
                              <h4 class="qx-font-500"><?php echo JText::_("COM_QUIX_CONVERSION_TITLE"); ?></h4>
                              <p class="qx-subtitle-text"><?php echo JText::_("COM_QUIX_CONVERSION_DESC"); ?></p>
                              <a class="qx-button qx-label-danger qx-padding-small qx-border-rounded qx-padding-inline-small qx-padding-block-x-small qx-hover-clr-white" href="https://www.themexpert.com/quix-pagebuilder?utm_medium=button&utm_campaign=quix-pro&utm_source=admin-panel&utm_content=upgrade-now" target="_blank">
                                <svg width="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.8306 3.443C12.6449 3.16613 12.3334 3 12.0001 3C11.6667 3 11.3553 3.16613 11.1696 3.443L7.38953 9.07917L2.74781 3.85213C2.44865 3.51525 1.96117 3.42002 1.55723 3.61953C1.15329 3.81904 0.932635 4.26404 1.01833 4.70634L3.70454 18.5706C3.97784 19.9812 5.21293 21 6.64977 21H17.3504C18.7872 21 20.0223 19.9812 20.2956 18.5706L22.9818 4.70634C23.0675 4.26404 22.8469 3.81904 22.4429 3.61953C22.039 3.42002 21.5515 3.51525 21.2523 3.85213L16.6106 9.07917L12.8306 3.443Z" fill="#fff"></path> </g></svg>
                                <span style="margin-left: 10px"><?php echo JText::_("COM_QUIX_POR_MESSAGE"); ?></span>
                              </a>
                          </div>

                          <ul style="color: var(--qx-admin-text-clr-dark); font-weight: 500; margin-bottom: 10px" class="qx-list">
                            <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo JText::_("COM_QUIX_JSON_IMPORT_EXPORT"); ?></li>
                            <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo JText::_("COM_QUIX_SEO_SETTINGS"); ?></li>
                            <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo JText::_("COM_QUIX_CUSTOM_CODE"); ?></li>
                            <li><span style="width:25px; height:25px;" class="qx-margin-small-right qx-icon-button qx-icon qx-button-default" qx-icon="check"><svg width="15" height="15" viewBox="0 0 20 20"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span><?php echo JText::_("COM_QUIX_COPY_PASTE"); ?></li>
                          </ul>
                      </div>

                  </div>
		      <?php endif; ?>

              <div class="qx-card qx-padding-medium qx-background-white qx-border-remove">
                <ul class="qx-iconnav qx-iconnav-vertical qx-list-divider">
                    <li>
                        <a href="https://www.themexpert.com/support" target="_blank" qx-icon="icon: plus" class="qx-icon">
                            <span class="qx-icon-button">
                                <svg width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" stroke-width="1.1" cx="10" cy="10" r="9"></circle><circle cx="9.99" cy="14.24" r="1.05"></circle><path fill="none" stroke="#000" stroke-width="1.2" d="m7.72,7.61c0-3.04,4.55-3.06,4.55-.07,0,.95-.91,1.43-1.49,2.03-.48.49-.72.98-.78,1.65-.01.13-.02.24-.02.35"></path></svg>
                            </span>
                            <span class="qx-text-default qx-margin-small-left"><?php echo JText::_("COM_QUIX_HELP_CENTER"); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/@ThemeXpert/videos" target="_blank" qx-icon="icon: plus" class="qx-icon">
                            <span class="qx-icon-button">
                                <svg width="20" height="20" viewBox="0 0 20 20"><path d="M15,4.1c1,0.1,2.3,0,3,0.8c0.8,0.8,0.9,2.1,0.9,3.1C19,9.2,19,10.9,19,12c-0.1,1.1,0,2.4-0.5,3.4c-0.5,1.1-1.4,1.5-2.5,1.6 c-1.2,0.1-8.6,0.1-11,0c-1.1-0.1-2.4-0.1-3.2-1c-0.7-0.8-0.7-2-0.8-3C1,11.8,1,10.1,1,8.9c0-1.1,0-2.4,0.5-3.4C2,4.5,3,4.3,4.1,4.2 C5.3,4.1,12.6,4,15,4.1z M8,7.5v6l5.5-3L8,7.5z"></path></svg>
                            </span>
                            <span class="qx-text-default qx-margin-small-left"><?php echo JText::_("COM_QUIX_YOUTUBE"); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.themexpert.com/blog" target="_blank" qx-icon="icon: plus" class="qx-icon">
                            <span class="qx-icon-button">
                                <svg width="20" height="20" viewBox="0 0 20 20"><ellipse fill="none" stroke="#000" cx="10" cy="4.64" rx="7.5" ry="3.14"></ellipse><path fill="none" stroke="#000" d="M17.5,8.11 C17.5,9.85 14.14,11.25 10,11.25 C5.86,11.25 2.5,9.84 2.5,8.11"></path><path fill="none" stroke="#000" d="M17.5,11.25 C17.5,12.99 14.14,14.39 10,14.39 C5.86,14.39 2.5,12.98 2.5,11.25"></path><path fill="none" stroke="#000" d="M17.49,4.64 L17.5,14.36 C17.5,16.1 14.14,17.5 10,17.5 C5.86,17.5 2.5,16.09 2.5,14.36 L2.5,4.64"></path></svg>
                            </span>
                            <span class="qx-text-default qx-margin-small-left"><?php echo JText::_("COM_QUIX_BLOG"); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/groups/QuixUserGroup" target="_blank" qx-icon="icon: plus" class="qx-icon">
                            <span class="qx-icon-button">
                                <svg width="20" height="20" viewBox="0 0 20 20"><path d="M11,10h2.6l0.4-3H11V5.3c0-0.9,0.2-1.5,1.5-1.5H14V1.1c-0.3,0-1-0.1-2.1-0.1C9.6,1,8,2.4,8,5v2H5.5v3H8v8h3V10z"></path></svg>
                            </span>
                            <span class="qx-text-default qx-margin-small-left"><?php echo JText::_("COM_QUIX_FACEBOOK_COMMUNITY"); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
          </div>
      </div>

      <?php echo QuixHelper::getFooterLayout(); ?>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirection; ?>" />
      <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
  </form>

    <?php echo $this->loadTemplate('new'); ?>
</div>
<script type="text/javascript">
    Joomla.orderTable = function() {
        let table = document.getElementById('sortTable');
        let direction = document.getElementById('directionTable');
        let order = table.options[table.selectedIndex].value;
        let newDirection;
        if (order != '<?php echo $listOrder; ?>') {
            newDirection = 'asc';
        }
        else {
            newDirection = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, newDirection, '');
    };
</script>

