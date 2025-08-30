<?php
/**
 * @package     Jvextensions.Plugin
 * @subpackage  Captcha
 *
 * @copyright   (C) 2021 JV-Extensions.com. <https://www.jv-extensions.com>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\String\StringHelper;

class PlgCaptchaJvmath extends CMSPlugin
{
    protected $autoloadLanguage = true;

    public function onInit($id = 'plg_captcha_jvmath')
    {
        return true;
    }

    public function onDisplay($name = null, $id = 'plg_captcha_jvmath', $class = ''): string
    {
        $opType = strip_tags($this->params->get('op_type'));
        $opComplexity = strip_tags($this->params->get('op_complexity'));

        $output = array();

        switch ($opType) {
            case 'M':{
                $output = $this->multiply($opComplexity);
                break;
            }
            case 'AM':{
                $output = $this->addAndMultiply($opComplexity);
                break;
            }
            case 'A':
            default: {
                $output = $this->add($opComplexity);
                break;
            }
        }

        return '
            <div id="' . $id . '">
                ' . Text::_('PLG_CAPTCHA_JVMATH_WHATIS') . ' ' . $output['out'] . '
                <input type="text" value="" name="plg_captcha_jvmath_cresponse" id="plg_captcha_jvmath_cresponse" class="required form-control" size="30" />
                <input type="hidden" value="' . md5($output['res']) . '" name="plg_captcha_jvmath_challenge" id="plg_captcha_jvmath_challenge" />
            </div>
        ';
    }

    private function multiply($opComplexity): array
    {
        $res = 0;
        $out = '';

        switch ($opComplexity) {
            case 'M': {
                $n1 = rand(100, 999);
                $n2 = rand(100, 999);
                $res = $n1 * $n2;
                $out = $n1 . ' x ' . $n2 . ' = ';
                break;
            }
            case 'H': {
                $n1 = rand(1, 99);
                $n2 = rand(1, 99);
                $n3 = rand(1, 99);
                $res = $n1 * $n2 * $n3;
                $out = $n1 . ' x ' . $n2 . ' x ' . $n3 . ' = ';
                break;
            }
            case 'S':
            default: {
                $n1 = rand(1, 10);
                $n2 = rand(1, 10);
                $res = $n1 * $n2;
                $out = $n1 . ' x ' . $n2 . ' = ';
                break;
            }
        }

        return ['res' => $res, 'out' => $out];
    }

    private function addAndMultiply($opComplexity): array
    {
        $res = 0;
        $out = '';

        switch ($opComplexity) {
            case 'M': {
                $n1 = rand(1, 99);
                $n2 = rand(1, 99);
                $n3 = rand(1, 99);
                $res = $n1 * $n2 + $n3;
                $out = '(' . $n1 . ' x ' . $n2 . ') + ' . $n3 . ' = ';
                break;
            }
            case 'H': {
                $n1 = rand(100, 999);
                $n2 = rand(100, 999);
                $n3 = rand(100, 999);
                $res = $n1 * $n2 + $n3;
                $out = '(' . $n1 . ' x ' . $n2 . ') + ' . $n3 . ' = ';
                break;
            }
            case 'S':
            default: {
                $n1 = rand(1, 10);
                $n2 = rand(1, 10);
                $n3 = rand(1, 10);
                $res = $n1 * $n2 + $n3;
                $out = '(' . $n1 . ' x ' . $n2 . ') + ' . $n3 . ' = ';
                break;
            }
        }

        return ['res' => $res, 'out' => $out];
    }

    private function add($opComplexity): array
    {
        $res = 0;
        $out = '';

        switch ($opComplexity) {
            case 'M': {
                $n1 = rand(100, 999);
                $n2 = rand(100, 999);
                $res = $n1 + $n2;
                $out = $n1 . ' + ' . $n2 . ' = ';
                break;
            }
            case 'H': {
                $n1 = rand(1, 999);
                $n2 = rand(1, 999);
                $n3 = rand(1, 999);
                $res = $n1 + $n2 + $n3;
                $out = $n1 . ' + ' . $n2 . ' + ' . $n3 . ' = ';
                break;
            }
            case 'S':
            default: {
                $n1 = rand(1, 99);
                $n2 = rand(1, 99);
                $res = $n1 + $n2;
                $out = $n1 . ' + ' . $n2 . ' = ';
                break;
            }
        }

        return ['res' => $res, 'out' => $out];
    }

    public function onCheckAnswer($code = null): bool
    {
        $input = Factory::getApplication()->input;

        $challenge = StringHelper::trim($input->get('plg_captcha_jvmath_challenge', '', 'string'));
        $response = StringHelper::trim($input->get('plg_captcha_jvmath_cresponse', '', 'string'));

        if (empty($challenge) || empty($response)) {
            throw new \RuntimeException(Text::_('PLG_CAPTCHA_JVMATH_ERR_EMPTY_RESULT'), 500);
        }

        if ($challenge != md5($response)) {
            throw new \RuntimeException(Text::_('PLG_CAPTCHA_JVMATH_ERR_INCORRECT_RESULT'), 500);
        }

        return true;
    }
}