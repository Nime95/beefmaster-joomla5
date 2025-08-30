<?php

use QuixNxt\AssetManagers\ScriptManager;
use QuixNxt\AssetManagers\StyleManager;

if ( ! defined('LOAD_VIDEO_CSS') && elementRequestedFromBuilder()) {
    define('LOAD_VIDEO_CSS', true);
//    ScriptManager::getInstance()->addUrl(QuixAppHelper::getQuixUrl('visual-builder/elements/video/assets/plyr.js'));
//    StyleManager::getInstance()->addUrl(QuixAppHelper::getQuixUrl('visual-builder/elements/video/assets/plyr.css'));

    ScriptManager::getInstance()->addUrl(\JUri::root(true) . '?quix-asset=assets/plyr.js&path=visual-builder&name=video');
    StyleManager::getInstance()->addUrl(\JUri::root(true) . '?quix-asset=assets/plyr.css&path=visual-builder&name=video');
}
