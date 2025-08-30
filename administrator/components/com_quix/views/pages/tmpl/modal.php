<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */
defined('_JEXEC') or die;

// No direct access
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Factory;

$app = Factory::getApplication();

if ($app->isClient('site'))
{
	JSession::checkToken('get') or die(\Joomla\CMS\Language\Text::_('JINVALID_TOKEN'));
}


\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
//\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip');
//\Joomla\CMS\HTML\HTMLHelper::_('behavior.framework', true);
//\Joomla\CMS\HTML\HTMLHelper::_('formbehavior.chosen', 'select');

$function  = $app->input->getCmd('function', 'jSelectPage');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_quix&view=pages&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>" method="post"
	  name="adminForm" id="adminForm"
	  style="padding: 15px;margin: 0px;">

        <div class="js-stools">
            <div class="js-stools-container-bar">
                <div id="filter-bar" class="btn-toolbar btn-group">
                    <div class="filter-search btn-group pull-left input-group">

                        <input
                                class="form-control js-stools-search-string"
                                type="text" name="filter_search" id="filter_search"
                                placeholder="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER'); ?>"
                                value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                                title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER'); ?>"/>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary hasTooltip" title="<?php echo \Joomla\CMS\HTML\HTMLHelper::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom">
                            <span class="icon-search"></span></button>
                        <button type="button" class="btn btn-primary hasTooltip" title="<?php echo \Joomla\CMS\HTML\HTMLHelper::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.getElementById('filter_search').value='';this.form.submit();">
                            <span class="icon-remove"></span></button>
                    </div>

                    <div class="btn-group pull-right hidden-phone">
                        <label for="limit"
                               class="element-invisible">
                            <?php echo \Joomla\CMS\Language\Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
                        </label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>

                    <div class="btn-group pull-right hidden-phone">
                        <label for="directionTable"
                               class="element-invisible">
                            <?php echo \Joomla\CMS\Language\Text::_('JFIELD_ORDERING_DESC'); ?>
                        </label>
                        <select name="directionTable" id="directionTable" class="input-medium form-select"
                                onchange="Joomla.orderTable()">
                            <option value=""><?php echo \Joomla\CMS\Language\Text::_('JFIELD_ORDERING_DESC'); ?></option>
                            <option value="asc" <?php echo $listDirn === 'asc' ? 'selected="selected"' : ''; ?>>
                                <?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_ORDER_ASCENDING'); ?>
                            </option>
                            <option value="desc" <?php echo $listDirn === 'desc' ? 'selected="selected"' : ''; ?>>
                                <?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_ORDER_DESCENDING'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="btn-group pull-right ordering-select">
                        <label for="directionTable"
                               class="element-invisible">
                            <?php echo \Joomla\CMS\Language\Text::_('JFIELD_ORDERING_DESC'); ?>
                        </label>
                        <select name="filter_published" id="filter_published" class="input-medium form-select"
                                onchange="this.form.submit()">
                            <?php echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', \Joomla\CMS\HTML\HTMLHelper::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		<div class="clearfix"></div>
		<div class="item-list">
			<table class="table table-striped" id="pageList">
				<thead>
					<tr>
						<?php if (isset($this->items[0]->id)): ?>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.`id`', $listDirn, $listOrder); ?>
						</th>
						<?php endif; ?>
						<th width="1%" class="nowrap center">
								<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort',  'COM_QUIX_PAGES_TITLE', 'a.`title`', $listDirn, $listOrder); ?>
						</th>
            <th class='left'>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort',  'COM_QUIX_PAGES_LANGUAGE', 'a.`language`', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<div class="navigation-wrapper">
							<?php echo $this->pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php
          $lang = '*';
          if ($item->language && JLanguageMultilang::isEnabled())
					{
						$tag = strlen($item->language);
						if ($tag === 5)
						{
							$lang = substr($item->language, 0, 2);
						}
						elseif ($tag === 6)
						{
							$lang = substr($item->language, 0, 3);
						}
						else {
							$lang = "*";
						}
					}
					elseif (!JLanguageMultilang::isEnabled())
					{
						$lang = "*";
					}


					?>
					<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo (int) $item->id; ?>
							</td>
							<td>
								<div class="btn-group">
									<?php echo \Joomla\CMS\HTML\HTMLHelper::_('jgrid.published', $item->state, $i, 'pages.', 0, 'cb'); ?>
									<a class="btn btn-micro"
										target="_blank"
										href="<?php echo JUri::root() . 'index.php?option=com_quix&view=page&id='.$item->id; ?>">
										<i class="icon-eye"></i>
									</a>
								</div>
							</td>
						<td>
							<a href="javascript:void(0)"
								onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', 0, null, '<?php echo $this->escape(\Joomla\CMS\Router\Route::_("index.php?option=com_quix&view=page&id=".$item->id)); ?>', '<?php echo $this->escape($lang); ?>', null);">
								<?php echo $this->escape($item->title); ?>
							</a>
							<span class="label<?php echo ($item->builder === 'classic' ? '' : ' label-info') ?>">
								<?php echo ucfirst($item->builder) ?>
							</span>
						</td>
						<td>
							<?php echo $item->language; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
</form>
<?php  if(JVERSION < 4): ?>
<style type="text/css">
#pageList{
	margin: 0px;
}
.item-list {
    padding: 20px;
    background: #fff;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 1px 5px 0 rgba(0,0,0,.12), 0 3px 1px -2px rgba(0,0,0,.2);
}
#filter-bar input {
    background: #ebecec;
    box-sizing: border-box;
    color: #fff;
    display: block;
    height: 36px;
    padding: 5px 10px;
    box-shadow: none;
    border: 1px solid #f1f1f1;
    font-size: 14px;
}
.chzn-container .chzn-single {
    background: #ebecec;
    border: none;
    border-radius: 2px;
    height: 35px;
    line-height: 35px;
    font-size: 14px;
    box-shadow: none;
}
.item-list table.table tr td,
.item-list table.table tr th {
    height: 45px;
    line-height: 45px;
    padding: 0 10px;
}
#filter-bar .btn-group .btn {
    min-width: 40px;
    background: rgba(158,158,158,.2);
    height: 36px;
    padding: 5px 10px;
    box-shadow: none;
    border: 1px solid #f1f1f1;
    font-size: 14px;
}
.navigation-wrapper nav{
	margin: 20px 0 0;
    line-height: normal;
    height: auto;
    text-align: center;
}
</style>
<?php else: ?>
    <style>
        #filter-bar > div{
            margin-right: 10px;
        }
    </style>
<?php endif; ?>
