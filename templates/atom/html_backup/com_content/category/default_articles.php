<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Multilanguage;

\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create some shortcuts.
$n          = count($this->items);
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$langFilter = false;

// Tags filtering based on language filter
if (($this->params->get('filter_field') === 'tag') && (Multilanguage::isEnabled()))
{
	$tagfilter = ComponentHelper::getParams('com_tags')->get('tag_list_language_filter');

	switch ($tagfilter)
	{
		case 'current_language' :
			$langFilter = \Joomla\CMS\Factory::getApplication()->getLanguage()->getTag();
			break;

		case 'all' :
			$langFilter = false;
			break;

		default :
			$langFilter = $tagfilter;
	}
}

// Check for at least one editable article
$isEditable = false;

if (!empty($this->items))
{
	foreach ($this->items as $article)
	{
		if ($article->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
}

// For B/C we also add the css classes inline. This will be removed in 4.0.
\Joomla\CMS\Factory::getDocument()->addStyleDeclaration('
.hide { display: none; }
.table-noheader { border-collapse: collapse; }
.table-noheader thead { display: none; }
.border-none {border: none;}
select#limit {
	background: #f5f6f8;
	padding: 4px 10px;
	color: #666;
	border: 1px solid #e5e5e5;
	font-size: inherit;
}
table.table-bordered {border: 2px solid #f1f1f1;}
table a {color: #9eacb6;text-decoration: none;}
');

$tableClass = $this->params->get('show_headings') != 1 ? ' table-noheader' : '';

$nullDate    = \Joomla\CMS\Factory::getDbo()->getNullDate();
$currentDate = \Joomla\CMS\Factory::getDate()->format('Y-m-d H:i:s');

?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
<?php if ($this->params->get('filter_field') !== 'hide' || $this->params->get('show_pagination_limit')) : ?>
	<fieldset class="filters btn-toolbar clearfix border-none">
		<legend class="hide"><?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_FORM_FILTER_LEGEND'); ?></legend>
		<?php if ($this->params->get('filter_field') !== 'hide') : ?>
			<div class="btn-group">
				<?php if ($this->params->get('filter_field') === 'tag') : ?>
					<select class="qx-select" name="filter_tag" id="filter_tag" onchange="document.adminForm.submit();">
						<option value=""><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_TAG'); ?></option>
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', \Joomla\CMS\HTML\HTMLHelper::_('tag.options', array('filter.published' => array(1), 'filter.language' => $langFilter), true), 'value', 'text', $this->state->get('filter.tag')); ?>
					</select>
				<?php elseif ($this->params->get('filter_field') === 'month') : ?>
					<select class="qx-select" name="filter-search" id="filter-search" onchange="document.adminForm.submit();">
						<option value=""><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_MONTH'); ?></option>
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', \Joomla\CMS\HTML\HTMLHelper::_('content.months', $this->state), 'value', 'text', $this->state->get('list.filter')); ?>
					</select>
				<?php else : ?>
					<label class="filter-search-lbl element-invisible" for="filter-search">
						<?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group qx-align-right">
				<label for="limit" class="qx-hidden element-invisible">
					<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
	</fieldset>

	<div class="control-group hide">
		<div class="controls">
			<button type="submit" name="filter_submit" class="qx-btn qx-btn-primary"><?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_FORM_FILTER_SUBMIT'); ?></button>
		</div>
	</div>

<?php endif; ?>

<?php if (empty($this->items)) : ?>
	<?php if ($this->params->get('show_no_articles', 1)) : ?>
		<p><?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>
<?php else : ?>
	<table class="category qx-table qx-table-striped qx-table-hover<?php echo $tableClass; ?> qx-table-divider qx-table-responsive table-bordered">
		<caption class="hide"><?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_CATEGORY_LIST_TABLE_CAPTION', $this->category->title); ?></caption>
		<thead>
			<tr>
				<th scope="col" id="categorylist_header_title">
					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder, null, 'asc', '', 'adminForm'); ?>
				</th>
				<?php if ($date = $this->params->get('list_show_date')) : ?>
					<th scope="col" id="categorylist_header_date">
						<?php if ($date === 'created') : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.created', $listDirn, $listOrder); ?>
						<?php elseif ($date === 'modified') : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.modified', $listDirn, $listOrder); ?>
						<?php elseif ($date === 'published') : ?>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.publish_up', $listDirn, $listOrder); ?>
						<?php endif; ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_author')) : ?>
					<th scope="col" id="categorylist_header_author">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits')) : ?>
					<th scope="col" id="categorylist_header_hits">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_votes', 0) && $this->vote) : ?>
					<th scope="col" id="categorylist_header_votes">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_CONTENT_VOTES', 'rating_count', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_ratings', 0) && $this->vote) : ?>
					<th scope="col" id="categorylist_header_ratings">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'COM_CONTENT_RATINGS', 'rating', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($isEditable) : ?>
					<th scope="col" id="categorylist_header_edit"><?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $article) : ?>
			<?php if ($this->items[$i]->state == 0) : ?>
				<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
			<?php else : ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
			<?php endif; ?>
			<td headers="categorylist_header_title" class="list-title">
				<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
					<a href="<?php echo \Joomla\CMS\Router\Route::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)); ?>">
						<?php echo $this->escape($article->title); ?>
					</a>
					<?php if (JLanguageAssociations::isEnabled() && $this->params->get('show_associations')) : ?>
						<?php $associations = ContentHelperAssociation::displayAssociations($article->id); ?>
						<?php foreach ($associations as $association) : ?>
							<?php if ($this->params->get('flags', 1) && $association['language']->image) : ?>
								<?php $flag = \Joomla\CMS\HTML\HTMLHelper::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
								&nbsp;<a href="<?php echo \Joomla\CMS\Router\Route::_($association['item']); ?>"><?php echo $flag; ?></a>&nbsp;
							<?php else : ?>
								<?php $class = 'label label-association label-' . $association['language']->sef; ?>
								&nbsp;<a class="<?php echo $class; ?>" href="<?php echo \Joomla\CMS\Router\Route::_($association['item']); ?>"><?php echo strtoupper($association['language']->sef); ?></a>&nbsp;
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php else : ?>
					<?php
					echo $this->escape($article->title) . ' : ';
					$menu   = \Joomla\CMS\Factory::getApplication()->getMenu();
					$active = $menu->getActive();
					$itemId = $active->id;
					$link   = new JUri(\Joomla\CMS\Router\Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
					$link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)));
					?>
					<a href="<?php echo $link; ?>" class="register">
						<?php echo \Joomla\CMS\Language\Text::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
					</a>
					<?php if (JLanguageAssociations::isEnabled() && $this->params->get('show_associations')) : ?>
						<?php $associations = ContentHelperAssociation::displayAssociations($article->id); ?>
						<?php foreach ($associations as $association) : ?>
							<?php if ($this->params->get('flags', 1)) : ?>
								<?php $flag = \Joomla\CMS\HTML\HTMLHelper::_('image', 'mod_languages/' . $association['language']->image . '.gif', $association['language']->title_native, array('title' => $association['language']->title_native), true); ?>
								&nbsp;<a href="<?php echo \Joomla\CMS\Router\Route::_($association['item']); ?>"><?php echo $flag; ?></a>&nbsp;
							<?php else : ?>
								<?php $class = 'label label-association label-' . $association['language']->sef; ?>
								&nbsp;<a class="' . <?php echo $class; ?> . '" href="<?php echo \Joomla\CMS\Router\Route::_($association['item']); ?>"><?php echo strtoupper($association['language']->sef); ?></a>&nbsp;
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($article->state == 0) : ?>
					<span class="list-published label label-warning">
								<?php echo \Joomla\CMS\Language\Text::_('JUNPUBLISHED'); ?>
							</span>
				<?php endif; ?>
				<?php if ($article->publish_up > $currentDate) : ?>
					<span class="list-published label label-warning">
								<?php echo \Joomla\CMS\Language\Text::_('JNOTPUBLISHEDYET'); ?>
							</span>
				<?php endif; ?>
				<?php if ($article->publish_down < $currentDate && $article->publish_down !== $nullDate) : ?>
					<span class="list-published label label-warning">
								<?php echo \Joomla\CMS\Language\Text::_('JEXPIRED'); ?>
							</span>
				<?php endif; ?>
			</td>
			<?php if ($this->params->get('list_show_date')) : ?>
				<td headers="categorylist_header_date" class="list-date small">
					<?php
					echo \Joomla\CMS\HTML\HTMLHelper::_(
						'date', $article->displayDate,
						$this->escape($this->params->get('date_format', \Joomla\CMS\Language\Text::_('DATE_FORMAT_LC3')))
					); ?>
				</td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_author', 1)) : ?>
				<td headers="categorylist_header_author" class="list-author">
					<?php if (!empty($article->author) || !empty($article->created_by_alias)) : ?>
						<?php $author = $article->author ?>
						<?php $author = $article->created_by_alias ?: $author; ?>
						<?php if (!empty($article->contact_link) && $this->params->get('link_author') == true) : ?>
							<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_WRITTEN_BY', \Joomla\CMS\HTML\HTMLHelper::_('link', $article->contact_link, $author)); ?>
						<?php else : ?>
							<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<td headers="categorylist_header_hits" class="list-hits">
							<span class="qx-badge qx-padding-small">
								<?php echo \Joomla\CMS\Language\Text::sprintf('JGLOBAL_HITS_COUNT', $article->hits); ?>
							</span>
						</td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_votes', 0) && $this->vote) : ?>
				<td headers="categorylist_header_votes" class="list-votes">
					<span class="qx-badge qx-padding-small">
						<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_VOTES_COUNT', $article->rating_count); ?>
					</span>
				</td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_ratings', 0) && $this->vote) : ?>
				<td headers="categorylist_header_ratings" class="list-ratings">
					<span class="qx-badge qx-padding-small">
						<?php echo \Joomla\CMS\Language\Text::sprintf('COM_CONTENT_RATINGS_COUNT', $article->rating); ?>
					</span>
				</td>
			<?php endif; ?>
			<?php if ($isEditable) : ?>
				<td headers="categorylist_header_edit" class="list-edit">
					<?php if ($article->params->get('access-edit')) : ?>
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('icon.edit', $article, $article->params); ?>
					<?php endif; ?>
				</td>
			<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Code to add a link to submit an article. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('icon.create', $this->category, $this->category->params); ?>
<?php endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
		<div class="qx-margin-large qx-margin-remove-bottom">

			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter qx-align-right">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>

			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
</form>
