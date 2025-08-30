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

namespace CodeAlfa\Component\JchOptimize\Administrator\Field;

use JchOptimize\Core\Admin\AdminHelper;
use JchOptimize\Core\FeatureHelpers\ResponsiveImages;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class CropgravityField extends JchMultiSelectWithOptionsField
{
    protected $type = 'cropgravity';

    protected function getInput(): string
    {
        if (!JCH_PRO) {
            return AdminHelper::proOnlyField();
        } else {
            return parent::getInput();
        }
    }

    protected function getLayoutData(): array
    {
        if (!JCH_PRO) {
            return parent::getLayoutData();
        }

        $breakpoint = ResponsiveImages::$breakpoints[0];

        return array_merge(
            parent::getLayoutData(),
            [
                'option1SubFieldLayout' => 'subfield.select',
                'option2SubFieldLayout' => 'subfield.text',
                'option1SubFieldClass' => 'jch-js-ieo has-select',
                'option2SubFieldClass' => 'jch-js-dontmove has-text-input',
                'option1Obj' => "{
                    type: \"select\", 
                    name: \"{$this->option1}\", 
                    htmlOptions: [
                        {value: \"West\", selected: \"\", text: \"Left\"},
                        {value: \"Center\", selected: \"selected\", text: \"Center\"},
                        {value: \"East\", selected: \"\", text: \"Right\"}
                    ]
                }",
                'option2Obj' => "{type: \"text\", name: \"{$this->option2}\", defaultValue: \"$breakpoint\"}",
            ]
        );
    }
}
