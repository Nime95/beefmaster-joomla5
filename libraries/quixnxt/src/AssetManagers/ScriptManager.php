<?php

namespace QuixNxt\AssetManagers;

use QuixNxt\Engine\Foundation\AssetManager;
use QuixNxt\Services\FileService;
use QuixNxt\Services\FontService;

class ScriptManager extends AssetManager
{
    protected $webfontConfig = [
        'shouldLoad' => false,
        'families'   => [],
    ];

    /**
     *
     * @param  string  $family
     *
     * @since 3.0.0
     */
    public function loadWebfont(string $family): void
    {
        $this->webfontConfig['shouldLoad'] = true;
        $this->webfontConfig['families'][] = $family;
    }

    /**
     * @return string
     *
     * @since 3.0.0
     */
    public function compile(): string
    {
        $scripts = parent::compile();

        return preg_replace('/\s+/', ' ', $scripts);
    }

    public function load(string $id): string
    {
        return '';
    }

    /**
     * @return string|null
     * @since 3.0.0
     */
    public function getWebFonts(): ?string
    {
        if(QUIX_GDPR_COMPLIANCE && $this->webfontConfig['shouldLoad']) {
          $fileSystem = new FileService();
          $fontService = new FontService();

          $fileMap = $fileSystem->getFontMapping();
          $fileMap['addedNewValue'] = false;

          try {
            $details = [];
            foreach ($this->webfontConfig['families'] as $family) {
              $details[] = $fontService->processFont($family, $fileMap);
            }

            if ($fileMap["addedNewValue"]) {
              unset($fileMap["addedNewValue"]);
              $fileSystem->saveFontMapping($fileMap);
            }
            return $this->generateHtml($details);

          } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
          }
        }
        elseif ($this->webfontConfig['shouldLoad']) {
            $families = json_encode($this->webfontConfig['families']);
            $script   = ";var qWebfont = document.createElement('script');";
            $script   .= "qWebfont.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';";
            $script   .= "qWebfont.onload = () => WebFont.load({ google: { families: {$families} } });";
            $script   .= "document.head.appendChild(qWebfont);";

            return $script;

            //return ";setTimeout(function(){jQuery.getScript('https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', function () { WebFont.load({ google: { families: {$families} } }); });}, 2000)";
        }

        return null;
    }

    private function generateHtml(array $details): string
    {
        $details = json_encode($details);

        return ";
          (() => {
          const details = {$details};
          document.fonts.ready.then(async () => {
            const loadedFonts = await Promise.all(details.map( ({fontKey, files: {css, font}}) => {
                const fontFamily = fontKey.split(':')[0];
                return loadFontWithLink({fontFamily, css, font});
            }));
            console.log(loadedFonts);

          });

          async function loadFontWithLink({fontFamily, css, font}) {
            const fontFace = new FontFace(fontFamily, 'url(".QUIXNXT_RELATIVE_CUSTOM_FONT_PATH."/' + font + ')');
            await fontFace.load();
            document.fonts.add(fontFace);

            return fontFamily + ' loaded';
          }
          })()
        ;";
    }


}
