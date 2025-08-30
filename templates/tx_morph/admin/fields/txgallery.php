<?php
    /**
	* @package ThemeXpert
	* @author ThemeXpert http://www.themexpert.com
	* @copyright Copyright (c) 2010 - 2016 ThemeXpert
	* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	* @credit: Helix Framework
	*/

    //no direct accees
    defined ('_JEXEC') or die ('resticted aceess');

	class JFormFieldTxgallery extends JFormField {

		protected $type = 'Txgallery';

		protected function getInput()
		{
			$doc = JFactory::getDocument();

			JHtml::_('jquery.framework');


            $path = str_replace (JPATH_ROOT, '', dirname(__DIR__));
            $path = str_replace ('\\', '/', substr($path, 1));

            $doc = JFactory::getDocument();
            $doc->addStyleSheet (JUri::root() . $path . '/assets/css/txgallery.css');
            $doc->addScript (JUri::root() . $path . '/assets/js/txgallery.js');

			$values = json_decode($this->value);
			
			if(is_countable($values) && count($values)) {
				$images = $this->element['name'] . '_images';
				$values = $values->$images;
			} else {
				$values = array();
			}

			$output  = '<div class="tx-gallery-field">';
			$output .= '<ul class="tx-gallery-items clearfix">';

			if(is_countable($values) && count($values)) {
				foreach ($values as $key => $value) {

					$data_src = $value;

					$src = JURI::root(true) . '/' . $value;

					$basename = basename($src);

					$thumbnail = JPATH_ROOT . '/' . dirname($value) . '/' . JFile::stripExt($basename) . '_thumbnail.' . JFile::getExt($basename);
					if(file_exists($thumbnail)) {
						$src = JURI::root(true) . '/' . dirname($value) . '/' . JFile::stripExt($basename) . '_thumbnail.' . JFile::getExt($basename);
					}

					$small_size = JPATH_ROOT . '/' . dirname($value) . '/' . JFile::stripExt($basename) . '_small.' . JFile::getExt($basename);
					if(file_exists($small_size)) {
						$src = JURI::root(true) . '/' . dirname($value) . '/' . JFile::stripExt($basename) . '_small.' . JFile::getExt($basename);
					}

					$output .= '<li data-src="' . $data_src . '"><a href="#" class="btn btn-mini btn-danger btn-remove-image">Delete</a><img src="'. $src .'" alt=""></li>';
				}
			}

			$output .= '</ul>';

			$output .= '<input type="file" class="tx-gallery-item-upload" accept="image/*" style="display:none;">';
			$output .= '<a class="btn btn-default btn-large btn-tx-gallery-item-upload" href="#"><i class="fa fa-plus"></i> Upload Images</a>';


			$output .= '<input type="hidden" name="'. $this->name .'" data-name="'. $this->element['name'] .'_images" id="' . $this->id . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8')
					. '"  class="form-field-txgallery">';
			$output .= '</div>';

			return $output;
		}
	}
