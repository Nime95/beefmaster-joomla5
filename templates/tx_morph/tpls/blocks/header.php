<?php
defined('_JEXEC') or die;

$header = $this->getParam('header_variation', 'default');

switch ($header) {
	case "header1":
		$this->loadBlock('header/header1');
		break;
	case "header2":
		$this->loadBlock('header/header2');
		break;
	case "header3":
		$this->loadBlock('header/header3');
		break;
  case "header4":
    $this->loadBlock('header/header4');
    break;
	case "header5":
    $this->loadBlock('header/header5');
    break;
	case "header6":
    $this->loadBlock('header/header6');
    break;
	case "header7":
    $this->loadBlock('header/header7');
    break;
	case "header8":
		$this->loadBlock('header/header8');
		break;
	case "header9":
		$this->loadBlock('header/header9');
		break;
	case "header10":
		$this->loadBlock('header/header10');
		break;
	case "header11":
		$this->loadBlock('header/header11');
		break;
	default:
		$this->loadBlock('header/default');
}
