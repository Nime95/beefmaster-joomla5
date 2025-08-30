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
use QuixNxt\Utils\ServiceHelper;

class FontService
{
    private $fileService;

    public function __construct()
    {
        $this->fileService = new FileService();
    }

    public function processFont(string $fontFamily, array &$fileMap): array
    {
        if (isset($fileMap[$fontFamily])) {
            return [
                'fontKey' => $fontFamily,
                'files' => $fileMap[$fontFamily]
            ];
        }
        $result = $this->downloadAndProcessFont($fontFamily);

        $fileMap[$fontFamily] = $result;
        $fileMap['addedNewValue'] = true;

        return [
            'fontKey' => $fontFamily,
            'files' => $fileMap[$fontFamily]
        ];
    }

    private function downloadAndProcessFont(string $fontFamily): array
    {
        $fileName = ServiceHelper::sanitizeFileName($fontFamily);
        $fontName = ServiceHelper::extractFontName($fontFamily);
        $url = ServiceHelper::generateGoogleFontsUrl($fontFamily);


        $css = file_get_contents($url);
        if ($css === false) {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $css = curl_exec($ch);
          $error = curl_error($ch);
          curl_close($ch);
          if($css === false) {
             echo "<script>console.error('Error: $error');</script>";
             return [];
          }
        }

        $fontUrl = ServiceHelper::extractSrcUrl($css);
        $fileExtension = ServiceHelper::getFileExtension($fontUrl);

        $fontFileName = "{$fileName}.{$fileExtension}";
        $cssFileName = "{$fileName}.css";

        $this->fileService->ensureFontDirectory($fontName);

        $css = preg_replace('/url\((.*?)\)/', "url('" . QUIXNXT_RELATIVE_CUSTOM_FONT_PATH . DIRECTORY_SEPARATOR . $fontName . DIRECTORY_SEPARATOR ."$fontFileName')", $css);

        $this->fileService->createCssFile($css, $cssFileName, $fontName);
        $this->fileService->downloadFont($fontUrl, $fontFileName, $fontName);

        return [
            'css' => $fontName . '/' . $cssFileName,
            'font' => $fontName . '/' . $fontFileName
        ];
    }
}
