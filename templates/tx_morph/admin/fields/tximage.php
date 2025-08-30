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

	class JFormFieldTximage extends JFormField
	{

		protected $type = 'Tximage';

		protected function getInput()
		{

            JFactory::getDocument()->addScriptDeclaration ( '
                TXAdmin = window.TXAdmin || {};
                TXAdmin.adminurl = \'' . JFactory::getURI()->toString() . '\';
                TXAdmin.rooturl  = \'' . JURI::root() . '\';
            ');

            $doc = JFactory::getDocument();

			JHtml::_('jquery.framework');

            // get template name
            $path = str_replace (JPATH_ROOT, '', dirname(__DIR__));
            $path = str_replace ('\\', '/', substr($path, 1));

            $doc = JFactory::getDocument();
            $doc->addStyleSheet (JUri::root() . $path . '/assets/css/tximage.css');
            $doc->addScript (JUri::root() . $path . '/assets/js/tximage.js');

//			$plg_path = JURI::root(true) . '/admin';
//			$doc->addScript($plg_path . '/assets/js/tximage.js');
//			$doc->addStyleSheet($plg_path . '/assets/css/tximage.css');

			if($this->value) {
				$class1 = ' hide';
				$class2 = '';
			} else {
				$class1 = '';
				$class2 = ' hide';
			}

			$output  = '<div class="tx-image-field clearfix">';
			$output .= '<div class="tx-image-upload-wrapper">';
			
			if($this->value) {
				$data_src = $this->value;
				$src = JURI::root(true) . '/' . $data_src;
				
				$basename = basename($data_src);
				$thumbnail = JPATH_ROOT . '/' . dirname($data_src) . '/' . JFile::stripExt($basename) . '_thumbnail.' . JFile::getExt($basename);
				
				if(file_exists($thumbnail)) {
					$src = JURI::root(true) . '/' . dirname($data_src) . '/' . JFile::stripExt($basename) . '_thumbnail.' . JFile::getExt($basename);
				}

				$output .= '<img src="'. $src .'" data-src="' . $data_src . '" alt="">';
			}

			$output .= '</div>';

			$output .= '<input type="file" class="tx-image-upload" accept="image/*" style="display:none;">';
			$output .= '<a class="btn btn-info btn-tx-image-upload'. $class1 .'" href="#"><i class="fa fa-plus"></i> Upload Image</a>';
			$output .= '<a class="btn btn-danger btn-tx-image-remove'. $class2 .'" href="#"><i class="fa fa-minus-circle"></i> Remove Image</a>';

			$output .= '<input type="hidden" name="'. $this->name .'" id="' . $this->id . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8')
					. '"  class="form-field-tximage">';
			$output .= '</div>';

			return $output;
		}
	}
