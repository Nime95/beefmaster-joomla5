<?php
/**
 * @package    plg_system_kickgdpr
 *
 * @author     Kicktemp GmbH <hello@kicktemp.com>
 * @copyright  Copyright © 2021 Kicktemp GmbH. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://kicktemp.com
 */

// No direct access to this file
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class plgSystemKickGDPRInstallerScript
{

	private $name                    = 'System Plugin KickGDPR';
	private $extname                 = 'plg_system_kickgdpr';
	private $previous_version        = '';
	private $previous_version_simple = '';
	private $dir = null;

	public function __construct()
	{
        // Define the minimum versions to be supported.
        $this->minimumJoomla = '4.0';
        $this->minimumPhp    = JOOMLA_MINIMUM_PHP;

		$this->dir = __DIR__;
	}

    function install($parent) {
        Factory::getDbo()->setQuery("UPDATE #__extensions SET enabled = 1 WHERE type = 'plugin' AND folder = 'system' AND element = 'kickgdpr'")->execute();
	}

    function preflight($type, $parent)
    {
        // Check for the minimum PHP version before continuing
        if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');
            return false;
        }
        // Check for the minimum Joomla version before continuing
        if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');
            return false;
        }

        return true;
    }

    function postflight($type, $parent)
	{
		$changelog  = $this->getChangelog();

		Factory::getApplication()->enqueueMessage($changelog, 'notice');

		return true;
	}

	private function getChangelog()
	{
		$changelog = file_get_contents($this->dir . '/CHANGELOG.txt');

		$changelog = "\n" . trim(preg_replace('#^.* \*/#s', '', $changelog));
		$changelog = preg_replace("#\r#s", '', $changelog);

		$parts = explode("\n\n", $changelog);

		if (empty($parts))
		{
			return '';
		}

		$this_version = '';

		$changelog = [];

		// Add first entry to the changelog
		$changelog[] = array_shift($parts);

		// Add extra older entries if this is an upgrade based on previous installed version
		if ($this->previous_version_simple)
		{
			if (preg_match('#^[0-9]+-[a-z]+-[0-9]+ : v([0-9\.]+(?:-dev[0-9]+)?)\n#i', trim($changelog[0]), $match))
			{
				$this_version = $match[1];
			}

			foreach ($parts as $part)
			{
				$part = trim($part);

				if ( ! preg_match('#^[0-9]+-[a-z]+-[0-9]+ : v([0-9\.]+(?:-dev[0-9]+)?)\n#i', $part, $match))
				{
					continue;
				}

				$changelog_version = $match[1];

				if (version_compare($changelog_version, $this->previous_version_simple, '<='))
				{
					break;
				}

				$changelog[] = $part;
			}
		}

		$changelog = implode("\n\n", $changelog);

		//  + Added   ! Removed   ^ Changed   # Fixed
		$change_types = [
			'+' => ['Added', 'success'],
			'!' => ['Removed', 'danger'],
			'^' => ['Changed', 'warning'],
			'#' => ['Fixed', 'info'],
		];
		foreach ($change_types as $char => $type)
		{
			$changelog = preg_replace(
				'#\n ' . preg_quote($char, '#') . ' #',
				"\n" . '<span class="label label-sm label-' . $type[1] . '" title="' . $type[0] . '">' . $char . '</span> ',
				$changelog
			);
		}

		$changelog = preg_replace('#see: (https://www\.kicktemp\.com[^ \)]*)#s', '<a href="\1" target="_blank">see documentation</a>', $changelog);

		$changelog = preg_replace(
			"#(\n+)([0-9]+.*?) : v([0-9\.]+(?:-dev[0-9]+)?)([^\n]*?\n+)#",
			'</pre>\1'
			. '<h3><span class="label label-inverse" style="font-size: 0.8em;">v\3</span>'
			. ' <small>\2</small></h3>'
			. '\4<pre>',
			$changelog
		);

		$changelog = str_replace(
			[
				'<pre>',
				'[FREE]',
				'[PRO]',
			],
			[
				'<pre style="line-height: 1.6em;">',
				'<span class="badge badge-sm badge-success">FREE</span>',
				'<span class="badge badge-sm badge-info">PRO</span>',
			],
			$changelog
		);

		$changelog = preg_replace(
			'#\[J([1-9][\.0-9]*)\]#',
			'<span class="badge badge-sm badge-default">J\1</span>',
			$changelog
		);

		$title = 'Latest changes for ' . JText::_($this->name);

		if ($this->previous_version_simple && version_compare($this->previous_version_simple, $this_version, '<'))
		{
			$title .= ' since v' . $this->previous_version_simple;
		}

		if ($this->previous_version_simple
			&& $this->getMajorVersionPart($this->previous_version_simple) < $this->getMajorVersionPart($this_version)
		)
		{
			Factory::getApplication()->enqueueMessage(JText::sprintf('RLI_MAJOR_UPGRADE', JText::_($this->name)), 'warning');
		}

		return '<h3>' . $title . ':</h3>'
			. $this->Cookies()
			. '<div style="max-height: 240px; padding-right: 20px; margin-right: -20px; overflow: auto;">'
			. $changelog
			. '</div>';
	}


	private function Cookies(){
		$svg = '<div style="max-width: 700px"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 500 60.7" xml:space="preserve"><style type="text/css">
	.st0{fill:#259AD7;}.st1{fill:#2D2A2A;}.st3{fill:#009EE3;}
</style><path id="logo" class="st0" d="M51.8,47l-4.6-5V18.6L26.9,6.8l-4.3,2.5v38.2L15,43.1V6l9.2-5.4c1.7-0.9,3.7-0.9,5.4,0l21.5,12.5 c1.5,0.9,2.7,3,2.7,4.7v24.9C53.8,44.3,53,46,51.8,47z M32,30.3l10.1-11l-6.4-3.7l-13,14.7l15.3,17.1l-10.9,6.4L6.7,42.1V18.6 l4.9-2.8V8L9.8,9l-7.1,4.1C1.2,14,0,16.1,0,17.9v24.9c0,1.7,1.2,3.9,2.7,4.7L24.2,60c1.7,0.9,3.7,0.9,5.4,0L49,48.8L32,30.3z"/>
<path class="st1" d="M68.6,19.4H73v22.5h-4.4V19.4z M73.2,30.3L82,19.4h5.1l-8.9,10.9l9.7,11.5h-5.5L73.2,30.3z"/><path class="st1" d="M101.9,19.4h4.4v22.5h-4.4V19.4z"/><path class="st1" d="M139.5,36.2l1.9,3.4c-2.2,1.7-5.1,2.7-8.4,2.7c-7.5,0-11.9-5.2-11.9-11.7s4.4-11.7,11.9-11.7
				c3.2,0,6.2,1,8.4,2.7l-1.9,3.5c-1.9-1.4-4.1-2.2-6.5-2.2c-4.6,0-7.5,3.5-7.5,7.7c0,4.1,2.9,7.7,7.5,7.7
				C135.4,38.3,137.8,37.5,139.5,36.2z"/>
			<path class="st1" d="M156.4,19.4h4.4v22.5h-4.4V19.4z M161,30.3l8.7-10.9h5.1l-8.9,10.9l9.7,11.5h-5.5L161,30.3z"/>
		<path class="st0" d="M198.2,29.8h10.9v9.6c-2.2,1.7-5.1,2.9-8.7,2.9c-7.5,0-11.9-5.2-11.9-11.7c0-6.5,4.4-11.7,11.9-11.7
			c2.8,0,5.4,0.7,7.5,2.1l-1.8,3.5c-1.5-0.9-3.4-1.5-5.5-1.5c-4.7,0-7.7,3.3-7.7,7.6c0,4.1,2.9,7.7,7.5,7.7c1.7,0,3.2-0.5,4.4-1.3
			v-4h-6.6V29.8z"/>
		<path class="st0" d="M223.9,41.8V19.4h8.5c7.3,0,11.5,4.8,11.5,11.2c0,6.4-4.2,11.2-11.5,11.2H223.9z M232.4,37.5
			c4.5,0,7.1-2.9,7.1-6.9c0-4-2.6-7-7.1-7h-4.1v13.8H232.4z"/>
		<path class="st0" d="M263.5,41.8h-4.4V19.4h9c4.7,0,7.7,3.2,7.7,7.4s-3.2,7.3-7.7,7.3h-4.6V41.8z M263.5,23.3v6.8h4.3
			c2.3,0,3.7-1.4,3.7-3.4s-1.3-3.4-3.7-3.4H263.5z"/>
		<path class="st0" d="M294.8,41.8h-4.4V19.4h9c4.7,0,7.6,3.2,7.6,7.4c0,3.2-2,5.8-5.1,6.8l5.5,8.3h-4.6l-5.2-7.7h-2.9V41.8z
			 M294.8,23.3v6.8h4.3c2.3,0,3.7-1.4,3.7-3.4s-1.3-3.4-3.7-3.4H294.8z"/>
		<path class="st3" d="M335.4,31.6h-7.8v-4.2h7.8v-7.7h4.3v7.7h7.8v4.2h-7.8v7.7h-4.3V31.6z"/>
			<path class="st3" d="M371.9,29.7l-0.7-0.7c-2.3-2.3-3-5.6-2.1-8.5c-2.3-0.5-4-2.6-4-5c0-2.8,2.3-5.1,5.1-5.1
				c2.6,0,4.7,1.9,5.1,4.3c2.8-0.7,5.8,0.1,8,2.3l0.3,0.3l-3.8,3.8l-0.3-0.3c-1.2-1.2-3.2-1.2-4.5,0c-1.2,1.2-1.2,3.2,0,4.5l0.7,0.7
				l3.8,3.8l4,4l-3.8,3.8l-4-4L371.9,29.7L371.9,29.7z"/>
			<path class="st3" d="M376.2,25.4l4-4l3.8-3.8l0.7-0.7c2.3-2.3,5.5-3,8.4-2.2c0.3-2.5,2.5-4.4,5.1-4.4c2.8,0,5.1,2.3,5.1,5.1
				c0,2.6-1.9,4.8-4.5,5.1c0.8,2.9,0.1,6.1-2.2,8.4l-0.3,0.3l-3.8-3.8l0.3-0.3c1.2-1.2,1.2-3.2,0-4.5c-1.2-1.2-3.2-1.2-4.5,0
				l-0.7,0.7l-3.8,3.8l-4,4L376.2,25.4L376.2,25.4z"/>
			<path class="st3" d="M393.3,44.6c-2.9,0.9-6.2,0.2-8.5-2.1l-0.3-0.3l3.8-3.8l0.3,0.3c1.2,1.2,3.2,1.2,4.5,0
				c1.2-1.2,1.2-3.2,0-4.5l-0.7-0.7l-3.8-3.8l-4-4l3.8-3.8l4,4l3.8,3.8l0.7,0.7c2.2,2.2,2.9,5.3,2.3,8c2.5,0.4,4.4,2.5,4.4,5.1
				c0,2.8-2.3,5.1-5.1,5.1C395.8,48.7,393.7,46.9,393.3,44.6L393.3,44.6z"/>
			<path class="st3" d="M391.8,33.9l-4,4l-3.8,3.8l-0.7,0.7c-2.2,2.2-5.3,2.9-8.1,2.2c-0.5,2.3-2.6,4-5,4c-2.8,0-5.1-2.3-5.1-5.1
				c0-2.4,1.7-4.5,4-5c-0.7-2.8,0-5.9,2.2-8.1l0.3-0.3l3.8,3.8l-0.3,0.3c-1.2,1.2-1.2,3.2,0,4.5c1.2,1.2,3.2,1.2,4.5,0l0.7-0.7
				l3.8-3.8l4-4L391.8,33.9L391.8,33.9z"/>
		<path class="st3" d="M421,23h19.9v4.2H421V23z M421,31.8h19.9v4.2H421V31.8z"/>
			<path class="st3" d="M499.2,24.4c-0.1-0.3-0.3-0.5-0.7-0.6c-0.3-0.1-0.6,0-0.9,0.3c-0.5,0.6-1.2,1-1.9,1.2
				c-2.2,0.6-4.5-0.7-5.1-2.9c0,0,0,0,0-0.1c-0.1-0.3-0.3-0.5-0.5-0.6c-0.2-0.1-0.5-0.1-0.8,0c-0.2,0.1-0.5,0.2-0.8,0.3
				c-2.2,0.6-4.5-0.7-5.1-2.9c-0.1-0.4-0.2-0.9-0.1-1.4c0-0.2-0.1-0.5-0.2-0.6c-0.2-0.2-0.4-0.3-0.6-0.3c-1.8-0.1-3.4-1.3-3.8-3.1
				c-0.3-1-0.2-2,0.3-2.9c0.1-0.3,0.1-0.6-0.1-0.9c-0.2-0.3-0.5-0.4-0.8-0.4c-1.4,0.1-2.8,0.3-4.1,0.7c-5.3,1.5-9.7,4.9-12.4,9.7
				c-2.7,4.8-3.4,10.4-1.9,15.7s4.9,9.7,9.7,12.4c4.8,2.7,10.4,3.4,15.7,1.9c5.3-1.5,9.7-4.9,12.4-9.7c2.7-4.8,3.4-10.4,1.9-15.7
				C499.2,24.5,499.2,24.5,499.2,24.4z M484.4,48.3c-10,2.8-20.4-3.1-23.2-13.1c-1.3-4.9-0.7-9.9,1.8-14.3c2.5-4.4,6.5-7.5,11.4-8.9
				c0.8-0.2,1.6-0.4,2.4-0.5c-0.2,0.9-0.2,1.9,0.1,2.8c0.6,2.2,2.4,3.8,4.6,4.3c0,0.4,0.1,0.7,0.2,1.1c0.9,3.2,4.2,5,7.3,4.1
				c0.1,0,0.1,0,0.2-0.1c1.2,2.6,4.2,4.1,7,3.3c0.6-0.2,1.1-0.4,1.6-0.7C499.8,35.9,494,45.6,484.4,48.3z"/>
			<ellipse class="st3" cx="472.6" cy="28.2" rx="1.6" ry="2.2"/>
			<ellipse class="st3" cx="484.6" cy="28.2" rx="1.6" ry="2.2"/>
			<path class="st3" d="M483.9,38.3c0,1.1-2.3-0.5-5-0.5s-4.8,1.6-4.8,0.5c0-1.1,2.2-3.3,4.9-3.3S483.9,37.2,483.9,38.3z"/>
</svg></div>';

		return $svg;
	}
}
