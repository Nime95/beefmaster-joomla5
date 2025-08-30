<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */

// No direct access
use QuixNxt\Utils\Asset;
use QuixNxt\Utils\Schema;

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;


\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
\Joomla\CMS\HTML\HTMLHelper::_('behavior.formvalidator');

$lang = \Joomla\CMS\Factory::getLanguage();
$languageFilePath = JPATH_SITE . '/language/en-GB/en-GB.com_quix.builder.ini';

if (file_exists($languageFilePath)) {
  $iniArray = parse_ini_file($languageFilePath);
  foreach ($iniArray as $key => $value) {
     Text::script($key);
  }
} else {
  echo '<script>console.error("Language file not found")</script>';
}

$params = ComponentHelper::getParams('com_quix');
$enable_alternate_page = boolval($params->get('enable_alternate_page', false));

if($enable_alternate_page) {
  $alternate_page_id = $params->get('alternate_page_id', false);
  $this->iframeUrl = $this->iframeUrl . '&Itemid='. $alternate_page_id;
}


$title     = JFilterOutput::stringURLSafe($this->item->title);
$actionUrl = 'index.php?option=com_quix&view=form&layout=edit&builder=frontend&id='.(int) $this->item->id;
echo "<script id='qx-theme-script' defer>
      // RUN THIS SCRIPT AS SOON AS THE PAGE LOADS TO SET THE THEME
      (() => {
        const theme = localStorage.getItem('qx-theme');
        if(theme) document.querySelector('html')?.setAttribute('data-theme', theme);
      })()
      </script>";
?>
  <!--load twig template and declare them as script-->
<?php echo $this->loadTemplate('modal'); ?>

  <form
          action="<?php echo \Joomla\CMS\Router\Route::_($actionUrl); ?>" method="post" enctype="multipart/form-data"
          name="adminForm" id="adminForm" class="qx-fb form-validate">

    <div class="qx-fb-frame">
        <div id="qx-fb-frame-toolbar">
          <div class="qx-skeleton-toolbar">

            <div>
              <div style="width: 55px; height: 28px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 70px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 70px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 70px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 70px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
            </div>

            <div>
              <div style="width: 100px; height: 25px;" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 50px; height: 25px;" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 50px; height: 25px;" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 50px; height: 25px;" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
            </div>

            <div>
<!--               TODO: Turn off the seo panel to see if any client complains-->
<!--              <div style="width: 100px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>-->
              <div style="width: 100px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 100px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
              <div style="width: 85px; height: 30px" class="qx-skeleton qx-skeleton-toolbar-btn">button</div>
             </div>

          </div>
        </div>

        <div class="qx-fb-frame-body-container">

          <!--LEFT SIDE BAR-->
            <aside id="qx-left-side-bar" class="qx-side-bar qx-side-bar--left">
                <div style="display: flex; justify-content: space-between; padding-inline: 15px;  padding-bottom: 10px" class="qx-skeleton-sidebar-title">
                  <div style="width: 130px; border-radius: 4px; " class="qx-skeleton">Sidebar Title</div>
                  <div style="width: 30px; border-radius: 4px; " class="qx-skeleton">btn</div>
                </div>
                <div class="qx-skeleton-layer">
                  <div class="qx-skeleton-layer-expand-btn">
                    <span class="qx-skeleton">Expand All</span>
                    <span class="qx-skeleton">BTN</span>
                  </div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 1</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 2</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 3</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 4</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 4</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 4</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 4</div>
                  <div class="qx-skeleton qx-skeleton-layer-item">Layer Item 4</div>
                </div>
              </aside>

            <!--PREVIEW SECTION-->
              <div style="width: 100%" id="qxui-preview-content" class="qx-fb-frame-preview qx-width-viewport qx-flex qx-margin-auto">
                <!--<iframex
                        title="quixFrame"
                        style="display: none"
                        data-src="<?php echo $this->iframeUrl; ?>"
                        name="quixFrame"
                        id="quix-iframe-wrapper"
                        sandbox="allow-top-navigation-by-user-activation allow-forms allow-popups allow-modals allow-pointer-lock
                allow-same-origin allow-scripts"
                        allowfullscreen="allowfullscreen"
                        allow="clipboard-read; clipboard-write">
                </iframe>-->
              </div>

              <!--RIGHT SIDE BAR-->
              <aside id="qx-right-side-bar" class="qx-side-bar qx-side-bar--right">
                  <div class="qx-skeleton-sidebar-title"><p class="qx-skeleton">Sidebar Title</p></div>
                  <div class="qx-skeleton-settings-segments">
                    <div class="qx-skeleton">Segment item 1</div>
                    <div class="qx-skeleton">Segment item 2</div>
                    <div class="qx-skeleton">Segment item 3</div>
                  </div>
                  <div class="qx-skeleton-settings">
                    <div class="qx-skeleton-settings-item">
                      <div style="height: 20px; width: 135px;" class="qx-skeleton">Label</div>
                      <div style="height: 20px; width: 35px;" class="qx-skeleton">btn</div>
                    </div>
                    <div class="qx-skeleton-settings-item">
                      <div class="qx-skeleton">Label</div>
                      <div class="qx-skeleton">Field</div>
                    </div>
                    <div class="qx-skeleton-settings-item">
                      <div class="qx-skeleton">Label</div>
                      <div class="qx-skeleton">Field</div>
                    </div>
                    <div class="qx-skeleton-settings-item">
                      <div class="qx-skeleton">Label</div>
                      <div class="qx-skeleton">Field</div>
                    </div>
                    <div class="qx-skeleton-settings-item">
                      <div class="qx-skeleton">Label</div>
                      <div class="qx-skeleton">Field</div>
                    </div>
                    <div class="qx-skeleton-settings-item">
                      <div class="qx-skeleton">Label</div>
                      <div class="qx-skeleton">Field</div>
                    </div>
                  </div>
              </aside>
        </div>

        <!-- <?php if (QuixHelperLicense::licenseStatus() !== 'pro'): ?>
          <div id="qx-notice-bar">
            <div>Activate Your License and Get Access to Premium Quix Templates, Support & Extension Updates.</div>
            <a href="<?php JUri::root() ?>/administrator/index.php?option=com_quix&view=config" target="_blank">CONNECT & ACTIVATE</a>
          </div>
        <?php endif; ?> -->

    </div>

    <input type="hidden" name="jform[data]" id="jform_data" value="" />
    <input type="hidden" name="jform[id]" id="jform_id" value="<?php echo (int) $this->item->id; ?>" />
    <input type="hidden" name="jform[title]" id="jform_title_hidden" value="<?php echo $this->item->title; ?>" />
    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
    <input type="hidden" id="jform_Itemid" value="<?php echo $this->Itemid; ?>" />
    <input type="hidden" name="jform[created_by]"
           value="<?php echo $this->item->created_by || Factory::getUser()->id; ?>" />
    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
    <input type="hidden" id="jform_task" name="task" value="" />
    <input type="hidden" id="jform_type" name="type" value="<?php echo $this->type ?>" />
    <input type="hidden" id="return_url" name="return" value="<?php echo $this->return_page; ?>" />

      <?php if (isset($this->item->type)) : ?>
        <input type="hidden" id="jform_template_type" name="jform[type]" value="<?php echo $this->item->type ?>" />
      <?php endif; ?>
    <input type="hidden" id="jform_builder_version" name="jform[builder_version]"
           value="<?php echo $this->item->builder_version ?>" />
    <input type="hidden" id="jform_token" name="<?php echo JSession::getFormToken(); ?>" value="1" />


      <?php echo $this->loadTemplate('options'); ?>
  </form>

<?php
$data       = QuixFrontendHelperAssets::processDataForBuilder($this->item->data, $this->item->builder_version);
$dataDebug  = QUIXNXT_DEBUG ? 'true' : 'false';
$root       = JUri::root();
$config     = ComponentHelper::getParams('com_media');
$imagePath  = $config->get('image_path', 'images');
$jmediaPath = $root.$imagePath;
$version = QuixAppHelper::getQuixMediaVersion();
$enable_seo = boolval($params->get('enable_seo', 0));

QuixFrontendHelperAssets::prepareApiScript();
QuixFrontendHelperAssets::loadLiveBuilderAssets();

Factory::getDocument()
        ->addStylesheet(Asset::getAssetUrl('/css/quix-builder.css'), ['version' => $version], [])
        ->addStyleSheet(Asset::getAssetUrl('/css/qxi.css'))
        ->addStyleSheet(\JUri::root()."media/quixnxt/css/qxicon.css", ['version' => $version])
        ->addScriptDeclaration('const JVERSION = "'.JVERSION.'";')
        ->addScriptDeclaration('const QuixPageAlias = "'.$title.'";')
        ->addScriptDeclaration("var QUIX_PAGE = {$data};")
        ->addScriptDeclaration("var QUIX_IFRAME_URL = '{$this->iframeUrl}'")
        ->addScriptDeclaration("var QUIXNXT_DEBUG = {$dataDebug};")
        ->addScriptDeclaration("var QUIX_ROOT_URL = '{$root}';")
        ->addScriptDeclaration("var QUIXNXT_JMEDIA_PATH_URL = '{$jmediaPath}/';")
        ->addScriptDeclaration("var QUIX_GDPR_COMPLIANCE = ".QUIX_GDPR_COMPLIANCE.";")
        ->addScriptDeclaration("window.qx_elements = ".Schema::getAvailableElements().';')
        ->addScriptDeclaration("window.addEventListener('load', function() {var qxAlerts = qxAlerts ?? []; qxAlerts.map(item => qxUIkit.modal.alert(item));qxAlerts = [];});")
        ->addScriptDeclaration("window.QUIX_SHAPES = ".json_encode(file_get_contents(JPATH_SITE.'/media/quixnxt/json/shapes.json')).';')
        ->addScriptDeclaration("window.ENABLE_SEO = ". ($enable_seo ? 1 : 0) .';');
?>

  <iframe
          title="q-store"
          data-src="https://getquix.net/media/quixblocks/js/qstore.html" id="q-store" style="display: none;"></iframe>

  <script>
      setTimeout(function() {
          document.getElementById('q-store').src = document.getElementById('q-store').attributes['data-src'].value;
      }, 5000);
  </script>

  <script data-cfasync="false" src="<?php echo Asset::getAssetUrl('/js/edit.js'); ?>" type="text/javascript" defer></script>
  <script data-cfasync="false" src="<?php echo Asset::getAssetUrl('/js/assets-helper.js'); ?>" type="text/javascript" defer></script>
  <script data-cfasync="false" src="<?php echo Asset::getAssetUrl('/builder/vendor.js'); ?>" type="text/javascript" defer></script>
  <script data-cfasync="false" src="<?php echo Asset::getAssetUrl('/js/qxfb.js') ?>" type="text/javascript" defer></script>
<?php
$session      = Factory::getSession();
$status       = $session->get('guide-quix-builder', 'show');
$tourComplete = Factory::getApplication()->input->cookie->get('guide-quix-builder', null);

// show_tour_guide
$config = ComponentHelper::getParams('com_quix');
$show_tour_guide = $tourComplete = $config->get('guide-quix-builder', 'show');

// we will show the notice once in lifetime for now. Later we will add a setting to show/hide the notice
// maybe on every update install, we can forcefully set it to show
QuixHelper::setComponentParams('guide-quix-builder', 'hide');
?>
<?php if ($tourComplete !== 'hide' && $status !== 'hide') { ?>
<!-- <script data-cfasync="false" src="https://cdn.jsdelivr.net/npm/shepherd.js@5.0.1/dist/js/shepherd.js" type="text/javascript" defer></script> -->
<?php //Factory::getDocument()->addScript(Asset::getAssetUrl('/js/guide.js')); ?>
<?php } ?>
<style>
    #qx-fb-frame-toolbar .qx-fb-toolbar__logo{
        background: url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/images/quix-logo.png') 7px 7px no-repeat;
    }
@font-face {
    font-family: 'qxi';
    font-display: swap;
    src: url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxi.ttf?cdywht') format('truetype'),
         url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxi.woff?cdywht') format('woff');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'qxuicon';
    font-display: swap;
    src:
        url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.ttf?a4jeee') format('truetype'),
        url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.woff?a4jeee') format('woff'),
        url('<?php echo Joomla\CMS\Uri\Uri::root();?>media/quixnxt/fonts/qxicon.svg?a4jeee#qxicon') format('svg');
    font-weight: normal;
    font-style: normal;
}
</style>
