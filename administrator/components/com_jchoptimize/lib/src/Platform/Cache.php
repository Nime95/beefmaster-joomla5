<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads.
 *
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2022 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Platform;

use JchOptimize\Core\Interfaces\Cache as CacheInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\Event\Dispatcher;
use Joomla\Registry\Registry;

\defined('_JEXEC') or exit('Restricted Access');
class Cache implements CacheInterface
{
    public static function cleanThirdPartyPageCache(): void
    {
        // Clean Joomla Cache
        $cache = Factory::getCache();
        $groups = ['page', 'pce'];
        foreach ($groups as $group) {
            $cache->clean($group);
        }
        // Clean LiteSpeed Cache
        if (\file_exists(\JPATH_PLUGINS.'/system/lscache/lscache.php')) {
            $dispatcher = new Dispatcher();
            $dispatcher->triggerEvent('onLSCacheExpired');
        }

        try {
            $app = Factory::getApplication();
            $app->setHeader('X-LiteSpeed-Purge', '*');
        } catch (\Exception $e) {
        }
    }

    public static function prepareDataFromCache(?array $data): ?array
    {
        // The following code searches for a token in the cached page and replaces it with the proper token.
        if (isset($data['body'])) {
            $token = Session::getFormToken();
            $search = '#<input type="?hidden"? name="?[\\da-f]{32}"? value="?1"?\\s*/?>#';
            $replacement = '<input type="hidden" name="'.$token.'" value="1">';
            $data['body'] = \preg_replace($search, $replacement, $data['body']);
        }

        return $data;
    }

    public static function outputData(array $data): void
    {
        $app = Factory::getApplication();
        if (!empty($data['headers'])) {
            foreach ($data['headers'] as $header) {
                $app->setHeader($header['name'], $header['value']);
            }
        }
        $app->setBody($data['body']);
        echo $app->toString((bool) $app->get('gzip'));
        $app->close();
    }

    public static function getCacheStorage($params): string
    {
        switch ($params->get('pro_cache_storage_adapter', 'filesystem')) {
            // Used in Unit testing.
            case 'blackhole':
                return 'blackhole';

            case 'global':
                $storageMap = ['file' => 'filesystem', 'redis' => 'redis', 'apcu' => 'apcu', 'memcached' => 'memcached', 'wincache' => 'wincache'];
                $app = Factory::getApplication();
                $handler = $app->get('cache_handler', 'file');
                if (\in_array($handler, \array_keys($storageMap))) {
                    return $storageMap[$handler];
                }
                // no break
            case 'filesystem':
            default:
                return 'filesystem';
        }
    }

    public static function isPageCacheEnabled(Registry $params, bool $nativeCache = \false): bool
    {
        return PluginHelper::isEnabled('system', 'jchoptimizepagecache');
    }

    public static function getCacheNamespace($pageCache = \false): string
    {
        if ($pageCache) {
            return 'jchoptimizepagecache';
        }

        return 'jchoptimizecache';
    }

    public static function isCaptureCacheIncompatible(): bool
    {
        return \false;
    }
}
