<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads.
 *
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Platform;

use JchOptimize\Core\Interfaces\Excludes as ExcludesInterface;

\defined('_JEXEC') or exit('Restricted access');
class Excludes implements ExcludesInterface
{
    /**
     * @param string $type
     * @param string $section
     */
    public static function body($type, $section = 'file'): array
    {
        if ('js' == $type) {
            if ('script' == $section) {
                return ['var mapconfig90', 'var addy'];
            }

            return ['assets.pinterest.com/js/pinit.js'];
        }
        if ('css' == $type) {
            return [];
        }

        return [];
    }

    public static function extensions(): string
    {
        // language=RegExp
        return '(?>components|modules|plugins/[^/]+|media(?!/system|/jui|/cms|/media|/css|/js|/images)(?:/vendor)?)/';
    }

    /**
     * @param string $type
     * @param string $section
     */
    public static function head($type, $section = 'file'): array
    {
        if ('js' == $type) {
            if ('script' == $section) {
                return [];
            }

            return ['plugin_googlemap3', '/jw_allvideos/', '/tinymce/'];
        }
        if ('css' == $type) {
            return [];
        }

        return [];
    }

    /**
     * @param string $url
     */
    public static function editors($url): bool
    {
        return \preg_match('#/editors/#i', $url);
    }

    public static function smartCombine(): array
    {
        return ['media/(?:jui|system|cms)/', '/templates/', '.'];
    }
}
