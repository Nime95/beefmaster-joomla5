<?php
/**
 * @package    plg_system_kickgdpr
 *
 * @author     Kicktemp GmbH <hello@kicktemp.com>
 * @copyright  Copyright © 2021 Kicktemp GmbH. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://kicktemp.com
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\Registry\Registry;

/**
 * Form Field class for Kicktemp Joomla! Extensions.
 * Provides a donation code check.
 */
class JFormFieldKickDonation extends JFormField
{
	protected $type = 'kickdonation';

	protected function getInput()
	{
		$html = '<a class="btn btn-success" href="https://www.paypal.me/kicktemp/5" target="_blank"><span class="icon-smiley-2 icon-white" aria-hidden="true"></span> 5 €</a> <a class="btn btn-success" href="https://www.paypal.me/kicktemp/10" target="_blank"><span class="icon-thumbs-up icon-white" aria-hidden="true"></span> 10 €</a> <a class="btn btn-success" href="https://www.paypal.me/kicktemp/" target="_blank"><span class="icon-star icon-white" aria-hidden="true"></span> # €</a>';
		return $html;
	}

	protected function getLabel()
	{
		return JText::_('KICKDONATION');
	}
}
