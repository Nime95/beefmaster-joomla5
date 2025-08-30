<?php
/**
 * @version    3.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

use QuixNxt\AssetManagers\ScriptManager;
use QuixNxt\AssetManagers\StyleManager;
use QuixNxt\Elements\ElementBag;
use QuixNxt\Elements\QuixElement;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

/**
 * Handle App API request through one controller
 *
 * @since  3.0.0
 */
class QuixControllerElement extends BaseController
{
    /**
     * Get a list of elements with all the necessary information
     *
     * @since 3.0.0
     */
    public function getElements(bool $isTrue = false): void
    {
        $app   = Factory::getApplication();
        $input = $app->input;

        $elementBag = ElementBag::getInstance();
        $missing    = explode(',', $input->get('elements', '', 'string'));

        $m = memory_get_usage();

        $templates = [
            'elements' => [],
            'js'       => [],
            'css'       => [],
            'special'  => [
                'animation.twig' => file_get_contents(QuixElement::QUIX_VISUAL_BUILDER_PATH.'/../shared/animation.twig'),
                'global.twig'    => file_get_contents(QuixElement::QUIX_VISUAL_BUILDER_PATH.'/../shared/global.twig'),
            ],
        ];
        foreach ($missing as $slug) {
            $element = $elementBag->get($slug);
            if($element){
                $templates['elements'][$slug] = $elementBag->get($slug)->getTemplates($isTrue);
            }
        }

        $templates['js'] = ScriptManager::getInstance()->getUrls();
        $templates['css'] = StyleManager::getInstance()->getUrls();

        $memory = QuixAppHelper::formatBytes(memory_get_usage() - $m);

        header('Content-Type: application/json');
        echo new \Joomla\CMS\Response\JsonResponse($templates, "Memory usage on generating templates: {$memory}");

        $app->close();
    }

    /**
     * Get a list of elements with all the necessary information with passing an additional parameter
     *
     * @since 1.5.6
     */
    public function getElementsData(): void
    {
        $this->getElements(true);
    }
}
