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

/**
 * Class plgSystemKickGdpr
 *
 * Enables Google Analytics functionality and adds an opt-out
 * link to disable it for GDPR law, with setting an cookie.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.kickgdpr
 * @since       3.8
 */
class PlgSystemKickGdpr extends JPlugin
{
	/**
	 * Application object
	 *
	 * @var    JApplicationCms
	 * @since  3.2
	 */
	protected $app;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin Trigger Content Code
	 *
	 * @var    String
	 * @since  3.2
	 */
	protected $trigger_content = null;

    /**
     * PlgSystemKickGdpr constructor.
     * @param object  &$subject  The object to observe -- event dispatcher.
     * @param object  $config    An optional associative array of configuration settings.
     * @throws ReflectionException
     */
    public function __construct(&$subject, $config)
	{
        parent::__construct($subject, $config);
	}

	/**
	 * onBeforeCompileHead
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onBeforeCompileHead()
	{
		if (!$this->app->isClient('site') || $this->params->get('disable_cookie', false))
		{
			return;
		}

		//
        $this->_addHelperFunctions();

        $type           = $this->params->get('compliance_type', '');
        $js = array(); // Javascript String Helper
        // Add
        $js[] = $this->_cookieHintJS();

        if ($type != '' && $type == 'opt-out')
        {
            $js[] = '  if (status != "deny") {';
        }

        if ($type != '' && $type == 'opt-in')
        {
            $js[] = '  if (status == "allow") {';
        }

        // Add Matomo Analytics to Head
        $js[] = $this->_addMatomo();

        // Add Google Analytics to Head
        $js[] = $this->_addGoogleAnalytics();

		// Add Google Tag Manager to Head
		$js[] = $this->_addGoogleTagManager();

        // Add Facebook Pixel Code to Head
        $js[] = $this->_addFacebookPixel();

        // Add Custom Code from Plugin Params to Head
        $js[] = $this->_addCustomCode();

        // Add Custom Code from Plugin Trigger onKickGDPR to Head
        $js[] = $this->_addExternalCode();


        if ($type != '' && ($type == 'opt-out' || $type == 'opt-in'))
        {
            $js[] = '  }';
        }

        $js[] = "}";
        $js[] = "";
        $js[] = "// Init handleCookies if the user doesn't choose any options";
        $js[] = "if (document.cookie.split(';').filter(function(item) {";
        $js[] = "    return item.indexOf('cookieconsent_status=') >= 0";
        $js[] = "}).length == 0) {";
        $js[] = "  handleCookies('notset');";
        $js[] = "};";

        $headjs = implode("\n", $js);

        JFactory::getDocument()->addScriptDeclaration($headjs);

        // Custom CSS
        $this->_addCustomCSS();
	}

    /**
     *
     */
    protected function _addHelperFunctions()
    {
        $headjs = array();

        if ($ga_code = $this->params->get('ga_code', false) && !$this->params->get('disable_ga', false))
        {
            $headjs[] = "";
            $headjs[] = "var disableStr = 'ga-disable-" . $ga_code . "';";
            $headjs[] = "";
            $headjs[] = "// Function to detect opted out users";
            $headjs[] = "function __kickgaTrackerIsOptedOut() {";
            $headjs[] = "	return document.cookie.indexOf(disableStr + '=true') > -1;";
            $headjs[] = "};";
            $headjs[] = "";
            $headjs[] = "// Disable tracking if the opt-out cookie exists.";
            $headjs[] = "if ( __kickgaTrackerIsOptedOut() ) {";
            $headjs[] = "	window[disableStr] = true;";
            $headjs[] = "};";
            $headjs[] = "";
            $headjs[] = "// Disable tracking if do not track active.";
            $headjs[] = "if (navigator.doNotTrack == 1) {";
            $headjs[] = "	window[disableStr] = true;";
            $headjs[] = "};";
            $headjs[] = "";
            $headjs[] = "function __kickgaTrackerOptout() {";
            $headjs[] = "   document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';";
            $headjs[] = "	window[disableStr] = true;";
            $headjs[] = "	alert('" . JText::_('PLG_SYSTEM_KICKGDPR_INFO_GA_OPTOUT_TEXT') . "');";
            $headjs[] = "}";
        }

        if (!$this->params->get('disable_ma', false))
        {
            $headjs[] = "    var _paq = window._paq || [];";
        }

        JFactory::getDocument()->addScriptDeclaration(implode("\n", $headjs));
    }

    /**
     *  Add Custom Code from Plugin Params to Head
     */
    protected function _addCustomCode()
    {
        $js = array();
        $customcode = $this->params->get('customcode', false);

        if ($customcode && $customcode != '')
        {
            $js[] = '    // Custom Code';
            $js[] = '    ' . $customcode;
            $js[] = '    // End Custom Code';
        }

        return implode("\n", $js);
    }

    /**
     * Add Custom Code from Plugin Trigger onKickGDPR to Head
     */
    protected function _addExternalCode()
    {
        $js = array();
        $trigger_content = $this->trigger_content;

        if ($trigger_content && $trigger_content != '')
        {
            $js[] = '    // Plugin Trigger Code';
            $js[] = '    ' . $trigger_content;
            $js[] = '    // End Plugin Trigger Code';
        }

        return implode("\n", $js);
    }

    /**
     *  Add Facebook Pixel Code to the head
     */
	protected function _addFacebookPixel()
    {
        $js = array();
        $pixel_id   = $this->params->get('pixel_id', false);

        if ($pixel_id && !$this->params->get('disable_facebook', false))
        {
            $js[] = "    // Facebook Pixel Code";
            $js[] = "    !function(f,b,e,v,n,t,s)";
            $js[] = "    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?";
            $js[] = "    n.callMethod.apply(n,arguments):n.queue.push(arguments)};";
            $js[] = "    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';";
            $js[] = "    n.queue=[];t=b.createElement(e);t.async=!0;";
            $js[] = "    t.src=v;s=b.getElementsByTagName(e)[0];";
            $js[] = "    s.parentNode.insertBefore(t,s)}(window,document,'script',";
            $js[] = "    'https://connect.facebook.net/en_US/fbevents.js');";
            $js[] = "    fbq('init', '" . $pixel_id . "');";
            if ($this->params->get('fb_pageview', true))
            {
                $js[] = "    fbq('track', 'PageView');";
            }
            if ($this->params->get('fb_viewcontent', false))
            {
                $js[] = "    fbq('track', 'ViewContent');";
            }
            if ($this->params->get('fb_search', false))
            {
                $js[] = "    fbq('track', 'Search');";
            }
            if ($this->params->get('fb_contact', false))
            {
                $js[] = "    fbq('track', 'Contact');";
            }
            if ($this->params->get('fb_lead', false))
            {
                $js[] = "    fbq('track', 'Lead');";
            }
            if ($this->params->get('fb_submitapplication', false))
            {
                $js[] = "    fbq('track', 'SubmitApplication');";
            }
            if ($this->params->get('fb_schedule', false))
            {
                $js[] = "    fbq('track', 'Schedule');";
            }
            if ($this->params->get('fb_findlocation', false))
            {
                $js[] = "    fbq('track', 'FindLocation');";
            }
            $js[] = "    // End Facebook Pixel Code";
            $js[] = "";
        }

        return implode("\n", $js);
    }

    /**
     *  Add Custom CSS to the head
     */
    protected function _addCustomCSS()
    {
        $customcss = $this->params->get('customcss', false);

        if ($customcss && $customcss != '')
        {
            $customcssarray = array();
            $customcssarray[] = '';
            $customcssarray[] = '/* Custom CSS */';
            $customcssarray[] = $customcss;
            $customcssarray[] = '/* End Custom CSS */';
            $customcssarray[] = '';

            $headcss = implode("\n", $customcssarray);

            JFactory::getDocument()->addStyleDeclaration($headcss);
        }
    }

    /**
     * Add Google Analytics to Head
     */
    protected function _addGoogleAnalytics()
    {
        $js = array();
        $ga_code    = $this->params->get('ga_code', false);

        if ($ga_code && !$this->params->get('disable_ga', false))
        {
            $js[] = "    // Google Analytics";
            $js[] = "    if (!window[disableStr]) {";
            $js[] = "    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){";
            $js[] = "    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),";
            $js[] = "    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
            $js[] = "    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');";
            $js[] = "";
            $js[] = "    ga('create', '" . $ga_code . "', 'auto')";
            if ($this->params->get('ga_forceSSL', true))
            {
                $js[] = "    ga('set', 'forceSSL', true);";
            }

            if ($this->params->get('ga_anonymizeIp', true))
            {
                $js[] = "    ga('set', 'anonymizeIp', true);";
            }

            if ($this->params->get('ga_displayfeatures', false))
            {
                $js[] = "    ga('require', 'displayfeatures');";
            }

            if ($this->params->get('ga_linkid', false))
            {
                $js[] = "    ga('require', 'linkid', 'linkid.js');";
            }

            $js[] = "    ga('send', 'pageview');";
            $js[] = "    }";
            $js[] = "    // End Google Analytics";
            $js[] = "";
        }

        return implode("\n", $js);
    }

	/**
	 * Add Google Tag Manager to Head
	 * since 3.3.0
	 */
	protected function _addGoogleTagManager()
	{
		$js = array();
		$gtm_id    = $this->params->get('gtm_id', false);

		if ($gtm_id && !$this->params->get('disable_gtm', false))
		{
			$js[] = "// Google Tag Manager";
			$js[] = "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\"gtm.start\":";
			$js[] = "new Date().getTime(),event:\"gtm.js\"});var f=d.getElementsByTagName(s)[0]";
			$js[] = "j=d.createElement(s),dl=l!=\"dataLayer\"?\"&l=\"+l:\"\";j.async=true;j.src=";
			$js[] = "\"https://www.googletagmanager.com/gtm.js?id=\"+i+dl;f.parentNode.insertBefore(j,f);";
			$js[] = "})(window,document,\"script\",\"dataLayer\",\"" . $gtm_id . "\");";
			$js[] = "// End Google Tag Manager";
			$js[] = "";
		}

		return implode("\n", $js);
	}

    /**
     * Add Matomo Analytics to Head
     */
    protected function _addMatomo()
    {
        $ma_url     = $this->params->get('ma_url', false);
        $ma_siteid  = $this->params->get('ma_siteid', false);
        $ma_domain  = $this->params->get('ma_domain', false);

        $js = array();

        if (($ma_url && $ma_domain && $ma_siteid) && !$this->params->get('disable_ma', false))
        {
            $js[] = "    // Matomo";

            $js[] = "    /* tracker methods like \"setCustomDimension\" should be called before \"trackPageView\" */";

            if ($this->params->get('ma_setdocumenttitle', true))
            {
            $js[] = "    _paq.push([\"setDocumentTitle\", document.domain + \"/\" + document.title]);";
            }

            if ($this->params->get('ma_setdomaincookie', true))
            {
            $js[] = "    _paq.push([\"setCookieDomain\", \"*." . $ma_domain ."\"]);";
            }

            if ($this->params->get('ma_setdomains', true))
            {
            $js[] = "    _paq.push([\"setDomains\", [\"*." . $ma_domain ."\"]]);";
            }

            if ($this->params->get('ma_clientdonottrack', true))
            {
            $js[] = "    _paq.push([\"setDoNotTrack\", true]);";
            }

            if ($this->params->get('ma_disablealltrack', true))
            {
            $js[] = "    _paq.push([\"disableCookies\"]);";
            }

            $js[] = "    _paq.push(['trackPageView']);";
            $js[] = "    _paq.push(['enableLinkTracking']);";
            $js[] = "    (function() {";
            $js[] = "        var u=\"//" . $ma_url . "/\";";
            $js[] = "        _paq.push(['setTrackerUrl', u+'matomo.php']);";
            $js[] = "        _paq.push(['setSiteId', '" . $ma_siteid . "']);";
            $js[] = "        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];";
            $js[] = "        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);";
            $js[] = "";
            $js[] = "    })();";
            $js[] = "    // End Matomo Code";
            $js[] = "";

        }

        return implode("\n", $js);
    }

    /**
     * @return array
     */
    protected function _cookieHintJS()
    {
        $js_css_source = $this->params->get('js_css_source', 'default');
        $jssrc = '';
        $csssrc = '';

        if ($js_css_source === 'default')
        {
            $jssrc  = 'plg_system_kickgdpr/cookieconsent.min.js';
            $csssrc = 'plg_system_kickgdpr/cookieconsent.min.css';
        }

        if ($js_css_source === 'cloudflare')
        {
            $jssrc = '//cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js';
            $csssrc = '//cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css';
        }

        if ($js_css_source === 'cloudflare' || $js_css_source === 'default')
        {
            JHtml::_('script', $jssrc, array('version' => 'auto', 'relative' => true));
            JHtml::_('stylesheet', $csssrc, array('version' => 'auto', 'relative' => true));
        }

        // Settings
        $href = '';
        $banner_color   = $this->params->get('banner_color', '#000000');
        $banner_text    = $this->params->get('banner_text', '#FFFFFF');
        $button_color   = $this->params->get('button_color', '#F1D600');
        $button_text    = $this->params->get('button_text', '#000000');
        $position       = explode(' ', $this->params->get('cookie_position', 'bottom'));
        $type           = $this->params->get('compliance_type', 'info');
        $theme          = $this->params->get('cookie_layout', 'block');
        $message        = addslashes(JText::_($this->params->get('message', 'PLG_SYSTEM_KICKGDPR_MESSAGE_DEFAULT')));
        $dismiss        = $this->params->get('dismiss', 'PLG_SYSTEM_KICKGDPR_DISMISS_DEFAULT');
        $allow          = $this->params->get('acceptbutton', 'PLG_SYSTEM_KICKGDPR_ACCEPTBUTTON_DEFAULT');
        $deny           = $this->params->get('denybutton', 'PLG_SYSTEM_KICKGDPR_DENYBUTTON_DEFAULT');
        $link           = $this->params->get('learnMore', 'PLG_SYSTEM_KICKGDPR_LEARNMORE_DEFAULT');
        $impront_link   = $this->params->get('imprintLinkText', 'PLG_SYSTEM_KICKGDPR_LEARNMORE_DESC');
        $revokeBtnText  = $this->params->get('revokeBtnText', 'PLG_SYSTEM_KICKGDPR_REVOKEBTNTEXT_DEFAULT');
        $expiryDays     = $this->params->get('expiryDays', 365);
        $message        = $this->_prepareMessageText($message);
        $lang_links     = $this->params->get('lang_links', false);
        $imprint_links   = $this->params->get('imprint_links', false);
        $revokeBtn   = $this->params->get('disable_revokebtn', false);

        if ($lang_links && count((array) $lang_links))
        {
            $lang = $this->app->getLanguage()->getTag();

            foreach ($lang_links as $lang_link)
            {
                if ($lang_link->language === '*' || $lang_link->language === $lang)
                {
                    $href = $lang_link->link;
                    $link_url = $lang_link->link_url;
                    $target = (isset($lang_link->link_url_target) && $lang_link->link_url_target != "")? $lang_link->link_url_target : '_blank';
                    $href = (isset($href) && '' != $href) ? JRoute::_("index.php?Itemid={$href}") : false;
                    $href = (isset($link_url) && '' != $link_url && !$href) ? $link_url : $href;
                }
            }
        }

	    if ($imprint_links && count((array) $imprint_links))
	    {
		    $lang = $this->app->getLanguage()->getTag();

		    foreach ($imprint_links as $imprint_link)
		    {
			    if ($imprint_link->language === '*' || $imprint_link->language === $lang)
			    {
				    $imprint_href = $imprint_link->link;
				    $imp_link_url = $imprint_link->link_url;
				    $imp_target = (isset($imprint_link->link_url_target) && $imprint_link->link_url_target != "")? $imprint_link->link_url_target : '_blank';
				    $imprint_href = (isset($imprint_href) && '' != $imprint_href) ? JRoute::_("index.php?Itemid={$imprint_href}") : false;
				    $imprint_href = (isset($imp_link_url) && '' != $imp_link_url && !$imprint_href) ? $imp_link_url : $imprint_href;
			    }
		    }
	    }

	    $messagetext = '<span id="cookieconsent:desc" class="cc-message">{{message}}';
	    if (isset($href) && $href !== '' && isset($target))
	    {
		    $messagetext .= '<a aria-label="learn more about cookies" role="button" tabindex="0" class="cc-link" href="' . JText::_($href) . '" target="' . $target . '">{{link}}</a>';
	    }

	    if (isset($imprint_href) && $imprint_href !== '' && isset($imp_target) && isset($href) && $href !== '' && isset($target))
	    {
	    	$messagetext .= ' | ';
	    }

	    if (isset($imprint_href) && $imprint_href !== '' && isset($imp_target))
	    {
		    $messagetext .= '<a role="button" tabindex="0" class="cc-link" href="' . JText::_($imprint_href) . '" target="' . $imp_target . '">{{imprint_link}}</a>';
	    }
	    $messagetext .= '</span>';

        if ($theme == 'wire')
        {
            $border = $button_color;
            $button_color = 'transparent';
        }

        $js = array();
        $js[] = '// Start Cookie Alert';
        $js[] = 'window.addEventListener("load", function(){';
        $js[] = 'window.cookieconsent.initialise({';
        $js[] = '  "palette": {';
        $js[] = '    "popup": {';
        $js[] = '      "background": "' . $banner_color . '",';
        $js[] = '      "text": "' . $banner_text . '"';
        $js[] = '    },';
        $js[] = '    "button": {';
        $js[] = '      "background": "' . $button_color . '",';
        $js[] = '      "text": "' . $button_text . '",';

        if (isset($border))
        {
            $js[] = '      "border": "' . $border . '"';
        }

        $js[] = '    }';
        $js[] = '  },';
        $js[] = '  "theme": "' . $theme . '",';
        $js[] = '  "position": "' . $position[0] . '",';

        if (isset($position[1]))
        {
            $js[] = '  "static": true,';
        }

        $rvkButton = ($revokeBtn)?'<div></div>':'<div class=\"cc-revoke {{classes}}\">' . addslashes(JText::_($revokeBtnText)) . '</div>';
        $js[] = '  "type": "' . $type . '",';
        $js[] = '  "revokable": false,';
        $js[] = '  "revokeBtn": "' . $rvkButton .'",';
        $js[] = '  "content": {';
        $js[] = '    "message": "' . $message . '",';
        $js[] = '    "dismiss": "' . addslashes(JText::_($dismiss)) . '",';
        $js[] = '    "allow": "' . addslashes(JText::_($allow)) . '",';
        $js[] = '    "deny": "' . addslashes(JText::_($deny)) . '",';
        $js[] = '    "link": "' . addslashes(JText::_($link)) . '",';
        $js[] = '    "imprint_link": "' . addslashes(JText::_($impront_link)) . '",';
        $js[] = '    "href": "' . addslashes(JText::_($href)) . '",';
        $js[] = '  },';
        $js[] = '  "cookie": {';
        $js[] = '    "expiryDays": ' . (int) $expiryDays;
        $js[] = '  },';
        $js[] = '  "elements": {';
        $js[] = '    "messagelink": "' . addslashes($messagetext). '"';
        $js[] = '  },';
        $js[] = '  onInitialise: function (status) {';
        $js[] = '    handleCookies(status);';
        $js[] = '  },';
        $js[] = '  onStatusChange: function (status, chosenBefore) {';
        $js[] = '    handleCookies(status);';

        if($this->params->get('page_refresh', false))
        {
        $js[] = '    setTimeout(function(){ location.reload(); }, ' . $this->params->get('refresh_timeout', 0) . ');';
        }

        $js[] = '  },';
        $js[] = '  onRevokeChoice: function () {';
        $js[] = '    handleCookies(status);';
        $js[] = '  }';
        $js[] = '})});';

        $js[] = "// End Cookie Alert";
        $js[] = "function handleCookies(status){";



        return implode("\n", $js);
    }

	/**
	 * onContentPrepare
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  void
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		if (!$this->app->isClient('site'))
		{
			return;
		}

		if ($context != 'com_content.article'  && $context != 'com_content.category')
		{
			return;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, '{kickgdpr_ma_iframe}') === false && strpos($article->text, '{kickgdpr_ga_optout}') === false && strpos($article->text, '{/kickgdpr_ga_optout}') === false)
		{
			return;
		}

		$gaOptoutOpenlink = '<a href="#" onClick="__kickgaTrackerOptout(); return false;" >';
		$gaOptoutCloselink = '</a>';
		$maIframe = $this->params->get('ma_iframecode', false);

		$article->text = str_replace('{kickgdpr_ma_iframe}', $maIframe, $article->text);
		$article->text = str_replace('{kickgdpr_ga_optout}', $gaOptoutOpenlink, $article->text);
		$article->text = str_replace('{/kickgdpr_ga_optout}', $gaOptoutCloselink, $article->text);
	}

	/**
	 * Plugin Trigger
	 *
	 * @param string $message
	 */
	protected function _prepareMessageText($message)
	{
		// Simple performance check to determine whether bot should process further
		if (strpos($message, '{kickgdpr_ma_iframe}') === false && strpos($message, '{kickgdpr_ga_optout}') === false && strpos($message, '{/kickgdpr_ga_optout}') === false)
		{
			return $message;
		}

		$gaOptoutOpenlink = '<a href=\"#\" onClick=\"__kickgaTrackerOptout(); return false;\">';
		$gaOptoutCloselink = '</a>';

		$message = str_replace('{kickgdpr_ga_optout}', $gaOptoutOpenlink, $message);
		$message = str_replace('{/kickgdpr_ga_optout}', $gaOptoutCloselink, $message);

		return $message;
	}

	/**
	 * Plugin Trigger
	 *
	 * @param string $cookieConsentCode
	 */
	public function onKickGDPR($cookieConsentCode = '')
	{
		if($cookieConsentCode != '')
		{
			$this->trigger_content .= $cookieConsentCode;
		}
	}

	/**
	 * Handle adding credentials to package download request.
	 *
	 * @param   string  $url      url from which package is going to be downloaded
	 * @param   array   $headers  headers to be sent along the download request (key => value format)
	 *
	 * @return boolean true if credentials have been added to request or not our business, false otherwise (credentials not set by user)
	 *
	 * @since   3.0
	 */
	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		$uri  = JUri::getInstance($url);
		$host = $uri->getHost();

		if (strpos($host, 'kicktemp.shop') === false
			&& strpos($host, 'plg_system_kickgdpr') === false)
		{
			return true;
		}

		if ($this->params->get('enable_statistics', false))
		{
			$uri->setVar('domain', JUri::getInstance()->getHost());
			$uri->setVar('cms_version', JVERSION);
			$uri->setVar('php_version', PHP_VERSION);
		}

		$url = $uri->toString();

		return true;
	}
}
