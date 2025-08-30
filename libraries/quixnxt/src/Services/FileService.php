<?php
/**
 * @package     QuixNxt\Services
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace QuixNxt\Services;

use Exception;

class FileService
{
    private $fontDir;
    private $mappingPath;

    public function __construct()
    {
        $this->mappingPath = QUIXNXT_FONTS_DIR . '/font_mapping.json';
        $this->ensureFontDirectory();
        $this->fontDir = QUIXNXT_FONTS_DIR;
    }

    public function ensureFontDirectory(string $additionalPath = ""): void
    {
       if (!file_exists(QUIXNXT_FONTS_DIR)) {
         mkdir(QUIXNXT_FONTS_DIR, 0777, true);
      }
       if(!empty($additionalPath)) {
         $newDir = $this->fontDir . DIRECTORY_SEPARATOR . $additionalPath;
         if (!file_exists($newDir)) {
            mkdir($newDir, 0777, true);
         }
       }
    }

    public function getFontMapping(): array
    {
        if (!file_exists($this->mappingPath)) {
            return [];
        }
        $json = file_get_contents($this->mappingPath);
        return json_decode($json, true);
    }

    public function saveFontMapping($mapping): void
    {
        file_put_contents($this->mappingPath, json_encode($mapping, JSON_PRETTY_PRINT));
    }

    public function createCssFile($css, $fileName, $fontName): string
    {
        $filePath = $this->fontDir . DIRECTORY_SEPARATOR . $fontName . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($filePath, $css);
        return $fileName;
    }

   public function downloadFont(string $url, string $fileName, string $fontName): string {
        $filePath = $this->fontDir . DIRECTORY_SEPARATOR . $fontName. DIRECTORY_SEPARATOR. $fileName;
        $url = trim($url, '"');

        // Initialize file handle for writing
        $fp = fopen($filePath, 'wb');
        if ($fp === false) {
            return $fileName;
        }

        try {
            $ch = curl_init($url);
            if ($ch === false) {
               return $fileName;
            }

            // Set CURL options
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FAILONERROR => true,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP
            ]);

            // Execute the download
            $success = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check for errors
            if ($success === false || $httpCode !== 200) {
                $error = curl_error($ch);
                $errno = curl_errno($ch);
                throw new Exception(
                    "Download failed: HTTP Code: $httpCode, Error: $error ($errno)"
                );
            }

            // Get file size to verify download
            $fileSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            if ($fileSize <= 0) {
                throw new Exception("Downloaded file is empty");
            }

            curl_close($ch);
            fclose($fp);

            // Verify file exists and has content
            if (!file_exists($filePath) || filesize($filePath) <= 0) {
                throw new Exception("File download appeared successful but file is missing or empty");
            }

            error_log("Font saved to $filePath");
            return $fileName;

        } catch (Exception $e) {
            // Clean up on any error
            if (isset($ch) && is_resource($ch)) {
                curl_close($ch);
            }
            if (is_resource($fp)) {
                fclose($fp);
            }
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return $fileName;
        }
    }

//    public function getFileContent($filePath)
//    {
//        if (!$this->pathExists($filePath)) {
//            throw new Exception("File not found: $filePath");
//        }
//        return file_get_contents($filePath);
//    }
}
