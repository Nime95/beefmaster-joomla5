<?php
/**
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org
 *------------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;

class TxTemplateHelper{

    public static function prepareHead($params)
    {
        //Body Font
        $webfonts = array();

        if ($params->get('enable_body_font')) {
            $webfonts['body'] = $params->get('body_font');
        }

        //Heading1 Font
        if ($params->get('enable_h1_font')) {
            $webfonts['h1'] = $params->get('h1_font');
        }

        //Heading2 Font
        if ($params->get('enable_h2_font')) {
            $webfonts['h2'] = $params->get('h2_font');
        }

        //Heading3 Font
        if ($params->get('enable_h3_font')) {
            $webfonts['h3'] = $params->get('h3_font');
        }

        //Heading4 Font
        if ($params->get('enable_h4_font')) {
            $webfonts['h4'] = $params->get('h4_font');
        }

        //Heading5 Font
        if ($params->get('enable_h5_font')) {
            $webfonts['h5'] = $params->get('h5_font');
        }

        //Heading6 Font
        if ($params->get('enable_h6_font')) {
            $webfonts['h6'] = $params->get('h6_font');
        }

        //Navigation Font
        if ($params->get('enable_navigation_font')) {
            $webfonts['.t3-navbar'] = $params->get('navigation_font');
        }

        //Custom Font
        //if ($params->get('enable_custom_font') && $params->get('custom_font_selectors')) {
        //    $webfonts[$params->get('custom_font_selectors')] = $params->get('custom_font');
        //}

        TxTemplateHelper::addGoogleFont($webfonts);
    }

    /**
     * Convert object to array
     *
     */
    public static function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $obj;
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = self::object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;
    }

    /**
     * Convert object to array
     *
     */
    public static function font_key_search($font, $fonts) {

        foreach ($fonts as $key => $value) {
            if($value['family'] == $font) {
                return $key;
            }
        }

        return 0;
    }

    /**
     * Add Google Fonts
     *
     * @param string $name  . Name of font. Ex: Yanone+Kaffeesatz:400,700,300,200 or Yanone+Kaffeesatz  or Yanone
     *                      Kaffeesatz
     * @param string $field . Applied selector. Ex: h1, h2, #id, .classname
     */


    public static function addGoogleFont($fonts)
    {

        $doc = JFactory::getDocument();
        $webfonts = '';
        $tpl_path = JPATH_BASE . '/templates/' . JFactory::getApplication()->getTemplate() . '/admin/webfonts/webfonts.json';
        $plg_path = JPATH_BASE . '/plugins/ajax/txadmin/assets/webfonts/webfonts.json';

        if(file_exists($tpl_path)) {
            $webfonts = file_get_contents($tpl_path);
        } else if (file_exists($plg_path)) {
            $webfonts = file_get_contents($plg_path);
        }

        //Families
        $families = array();
        foreach ($fonts as $key => $value)
        {
            $value = json_decode($value);

            if (isset($value->fontWeight) && $value->fontWeight)
            {
                $families[$value->fontFamily]['weight'][] = $value->fontWeight;
            }

            if (isset($value->fontSubset) && $value->fontSubset)
            {
                $families[$value->fontFamily]['subset'][] = $value->fontSubset;
            }
        }

        //Selectors
        $selectors = array();
        foreach ($fonts as $key => $value)
        {
            $value = json_decode($value);

            if (isset($value->fontFamily) && $value->fontFamily)
            {
                $selectors[$key]['family'] = $value->fontFamily;
            }

            if (isset($value->fontSize) && $value->fontSize)
            {
                $selectors[$key]['size'] = $value->fontSize;
            }

            if (isset($value->fontWeight) && $value->fontWeight)
            {
                $selectors[$key]['weight'] = $value->fontWeight;
            }
        }

        //Add Google Font URL
        foreach ($families as $key => $value)
        {
            $output = str_replace(' ', '+', $key);

            // Weight
            if($webfonts) {
                $fonts_array = self::object_to_array(json_decode($webfonts));
                $font_key = self::font_key_search($key, $fonts_array['items']);
                $weight_array = $fonts_array['items'][$font_key]['variants'];
                $output .= ':' . implode(',', $weight_array);
            } else {
                $weight = array_unique($value['weight']);
                if (isset($weight) && $weight)
                {
                    $output .= ':' . implode(',', $weight);
                }
            }

            // Subset
            $subset = array_unique($value['subset']);
            if (isset($subset) && $subset)
            {
                $output .= '&amp;subset=' . implode(',', $subset);
            }

            $doc->addStylesheet('//fonts.googleapis.com/css?family=' . $output);
        }

        //Add font to Selector
        foreach ($selectors as $key => $value)
        {

            if (isset($value['family']) && $value['family'])
            {

                $output = 'font-family:' . $value['family'] . ', sans-serif; ';

                if (isset($value['size']) && $value['size'])
                {
                    $output .= 'font-size:' . $value['size'] . 'px; ';
                }

                if (isset($value['weight']) && $value['weight'])
                {
                    $output .= 'font-weight:' . str_replace('regular', 'normal', $value['weight']) . '; ';
                }

                $selectors = explode(',', $key);

                foreach ($selectors as $selector)
                {
                    $style = $selector . '{' . $output . '}';
                    $doc->addStyledeclaration($style);
                }
            }
        }
    }
}
