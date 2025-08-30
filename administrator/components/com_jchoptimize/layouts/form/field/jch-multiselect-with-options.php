<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 *  @package   jchoptimize/core
 *  @author    Samuel Marshall <samuel@jch-optimize.net>
 *  @copyright Copyright (c) 2025 Samuel Marshall / JCH Optimize
 *  @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

use CodeAlfa\Component\JchOptimize\Administrator\Field\JchMultiSelectWithOptionsField;
use JchOptimize\Core\Admin\MultiSelectItems;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die('Restricted access');

extract($displayData);

/**
 * @var FormField $field The form field
 * @var Form $form
 * @var string $name Name of field
 * @var string $option1
 * @var string $option2
 * @var array $value
 * @var string $valueType
 * @var MultiSelectItems $multiSelect
 * @var array $dataAttributes
 * @var string $option1Header
 * @var string $option2Header
 * @var string $option1SubFieldLayout
 * @var string $option2SubFieldLayout
 * @var string $option1SubFieldClass
 * @var string $option2SubFieldClass
 * @var string $option1Obj
 * @var string $option2Obj
 * @var string $subFieldClass
 */
$value = array_values($value);
$nextIndex = count($value);
$inc1 = ++JchMultiSelectWithOptionsField::$incrementor;
$inc2 = ++JchMultiSelectWithOptionsField::$incrementor;
$i = 0;
$field->layout = 'joomla.form.field.list';
$fieldName = $field->fieldname;
?>
<script>
    let optionObj<?= $inc1 ?> = <?= $option1Obj ?>;
    let optionObj<?= $inc2; ?> = <?= $option2Obj ?>;
</script>
<fieldset id="fieldset-<?= $fieldName; ?>" data-index="<?= $nextIndex; ?>">
   <div class="jch-js-fieldset-children jch-js-excludes-header">
        <span class="jch-js-ieo-header">&nbsp;&nbsp;<?= $option1Header ?>&nbsp;&nbsp;&nbsp;</span>
        <span class="jch-js-dontmove-header">&nbsp;&nbsp;&nbsp;<?= $option2Header ?>&nbsp;&nbsp;</span>
    </div>
    <?php foreach ($value as $i => $v) :?>
        <?php if (isset($v[$valueType]) && is_string($v[$valueType])) : ?>
   <div id="div-<?= $fieldName; ?>-<?= $i ?>" class="jch-js-fieldset-children jch-js-excludes-container">
        <span class="jch-js-excludes">
            <span>
        <input type="text" readonly size="<?= strlen($v[$valueType]) ?>" value="<?= $v[$valueType]?>"
               name="jform[<?= $fieldName; ?>][<?= $i ?>][<?= $valueType; ?>]">
                <?= $multiSelect->{'prepare' . ucfirst((string)$dataAttributes['data-jch_group']) . 'Values'}(
                    $v[$valueType]
                ) ?>
        <button type="button" class="jch-multiselect-remove-button"
                onmouseup="jchMultiselect.removeJchJsOption('div-<?= $fieldName; ?>-<?= $i ?>',
                    'jform_<?= $fieldName; ?>')">
        </button>
            </span>
        </span>
            <?= LayoutHelper::render($option1SubFieldLayout, [
            'subFieldClass' => $option1SubFieldClass,
            'fieldName' => $fieldName,
            'i' => $i,
            'v' => $v,
            'option' => $option1,
            'class' => $subFieldClass,
       ], __DIR__); ?>
            <?= LayoutHelper::render($option2SubFieldLayout, [
            'subFieldClass' => $option2SubFieldClass,
            'fieldName' => $fieldName,
            'i' => $i,
            'v' => $v,
            'option' => $option2,
            'class' => $subFieldClass,
       ], __DIR__); ?>
</div>
        <?php endif; ?>
    <?php endforeach; ?>
</fieldset>
<div id="div-<?= $fieldName ?>">
    <?= $field->input ?>
    <img id="img-<?= $fieldName ?>" class="jch-multiselect-loading-image"
         src="<?= Uri::root(); ?>media/com_jchoptimize/core/images/exclude-loader.gif" />
    <button type="button" class="btn btn-sm btn-secondary jch-multiselect-add-button"
       onmousedown="jchMultiselect.addJchJsOption('jform_<?= $fieldName ?>', '<?= $fieldName ?>',
           '<?= $valueType ?>', optionObj<?= $inc1 ?>, optionObj<?= $inc2 ?>)" style="display: none;">Add item</button>
</div>
<script>
jQuery('#jform_<?= $fieldName ?>').on('change', function(evt, params){
    jchMultiselect.appendJchJsOption(
        'jform_<?= $fieldName ?>',
        '<?= $fieldName ?>',
       params,
        '<?= $valueType ?>',
        optionObj<?= $inc1 ?>,
        optionObj<?= $inc2 ?>
    );
});
</script>