<?php
/**
 * @package     QuixNxt\Utils
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace QuixNxt\Utils;

use Exception;

class ServiceHelper
{
    public static function sanitizeFileName(string $name): string
    {
        return preg_replace('/[: ]/', '_', $name);
    }
    public static function extractFontName($font) {
        return preg_replace('/\s+/', '_', explode(':', $font)[0]);
    }

    public static function generateGoogleFontsUrl(string $font): string
    {
        $baseUrl = 'https://fonts.googleapis.com/css?family=';
        return $baseUrl . urlencode($font);
    }

    public static function extractSrcUrl(string $css): string
    {
        if (preg_match('/url\((.*?)\)/', $css, $matches)) {
            $url = trim($matches[1], "'\""); // Remove quotes
            if (!preg_match('/^https?:\/\//', $url)) {
                $url = 'https:' . $url; // Ensure it's a full URL
            }
            return $url;
        }
        return '';
    }

    public static function getFileExtension(string $url): string
    {
        try {
            $url = trim($url, '"');
            $path = parse_url($url, PHP_URL_PATH);
            return pathinfo($path, PATHINFO_EXTENSION) ?: 'ttf';
        } catch (Exception $e) {
            error_log('Invalid URL: ' . $e->getMessage());
            return 'ttf';
        }
    }
}
