<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip');

$lang = \Joomla\CMS\Factory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

\Joomla\CMS\Factory::getDocument()->addStyleDeclaration('
#search-searchword {background: #f5f6f8;}
select#ordering {
	background: #f5f6f8;
	padding: 4px 10px;
	color: #666;
	border: 1px solid #e5e5e5;
	font-size: inherit;
	height: 40px;
}
');

?>
<form id="searchForm" action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_search'); ?>" method="post">
	<div class="qx-panel">

        <fieldset class="qx-fieldset">
            <div class="qx-grid-small" qx-grid>
                <div class="qx-width-expand@s">

                    <div class="qx-search qx-search-default qx-width-1-1">
                        <input id="search-searchword" class="qx-search-input" type="text" name="searchword" placeholder="<?php echo \Joomla\CMS\Language\Text::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>">
                    </div>
                    <input type="hidden" name="task" value="search">

                </div>
                <div class="qx-width-auto@s">

                    <button class="qx-button qx-button-primary qx-width-1-1" name="Search" onclick="this.form.submit()" qx-tooltip="<?php echo \Joomla\CMS\HTML\HTMLHelper::_('tooltipText', 'COM_SEARCH_SEARCH');?>"><?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_SUBMIT'); ?></button>

                </div>
            </div>
        </fieldset>

		<div class="qx-grid-row-small qx-child-width-auto qx-text-small qx-margin" qx-grid>
			<div>
				<?php if ($this->params->get('search_phrases', 1)) : ?>
					<fieldset class="qx-margin qx-fieldset">
						<div class="qx-grid-collapse qx-child-width-auto" qx-grid>
							<legend>
								<?php echo \Joomla\CMS\Language\Text::_('COM_SEARCH_FOR'); ?>
							</legend>
							<div>
								<?php echo $this->lists['searchphrase']; ?>
							</div>
						</div>
					</fieldset>
				<?php endif; ?>
			</div>

			<div>
				<?php if ($this->params->get('search_areas', 1)) : ?>
					<fieldset class="qx-margin qx-fieldset">
						<div class="qx-grid-collapse qx-child-width-auto" qx-grid>
							<legend>
								<?php echo \Joomla\CMS\Language\Text::_('COM_SEARCH_SEARCH_ONLY'); ?>
							</legend>
							<?php foreach ($this->searchareas['search'] as $val => $txt) : ?>
								<?php $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : ''; ?>
								<label for="area-<?php echo $val; ?>" class="checkbox">
									<input type="checkbox" name="areas[]" value="<?php echo $val; ?>" id="area-<?php echo $val; ?>" <?php echo $checked; ?> />
									<?php echo \Joomla\CMS\Language\Text::_($txt); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>
				<?php endif; ?>
			</div>
		</div>

		<div class="qx-grid-small qx-flex-middle qx-margin-medium <?php echo $this->params->get('pageclass_sfx'); ?>" qx-grid>
			<?php if (!empty($this->searchword)) : ?>
				<div class="qx-width-expand@s">
					<div class="qx-h3 "><?= \Joomla\CMS\Language\Text::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total) ?></div>
				</div>
			<?php endif; ?>

			<div class="qx-width-auto@s">
				<div class="qx-grid-small qx-child-width-auto" qx-grid>
					<div>
						<div><?= $this->lists['ordering'] ?></div>
					</div>
					<div>
					<div><?= $this->lists['limitBox'] ?></div>
				</div>
			</div>

			</div>
		</div>
	</div>
</form>
