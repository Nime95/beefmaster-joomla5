<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

try {
  $db    = Factory::getDbo();
  $query = $db->getQuery(true);
  $query->select($db->quoteName('params'))
        ->select($db->quoteName('name'))
        ->from($db->quoteName('#__quix_configs'))
        ->where($db->quoteName('name') . ' IN (' . $db->quote('username') . ', ' . $db->quote('key') . ')');

  $db->setQuery($query);
  $result = $db->loadAssocList('name', 'params');
  QuixHelperLicense::verifyApiKey($result['username'] ?? '', $result['key'] ?? '');
} catch (Exception $e) {
  echo "<script>console.log('Error: " . $e->getMessage() . "');</script>";
}

?>
<div id="qx-welcome-v3-wrapper" style="position: relative;">
  <style>
      #qx-welcome-v3-wrapper button {
          position: absolute;
          right: 0px;
          top: 0px;
          background: #fff;
          border: none;
          padding: 5px 10px;
      }

      #qx-welcome-v3 {
          background: linear-gradient(to left, #0195FF, #12D8FA 47%, #1FA2FF 100%);
          display: flex;
          justify-content: space-between;
          align-items: center;
          color: #fff;
          padding: 70px 100px;
          margin: 0px auto 30px;
          border-radius: 6px;
      }
      #qx-welcome-v3 .qx-cta-btn {
        background-color: #ffffff21;
        border: 1px solid #ffffff21;
        display: inline-block;
        font-weight: 500;
        padding: 0.5rem 2.5rem;
        transition: all 0.2s ease;
        box-shadow: #5A738D54 0px 10px 40px;
      }
      #qx-welcome-v3 .qx-cta-btn:hover {
        text-decoration: none;
        scale: 1.05;
      }

      #qx-welcome-v3 h3,
      #qx-welcome-v3 h4,
      #qx-welcome-v3 a {
          color: #fff
      }
      .qx-video-player {
          display: block;
          position: relative;
          min-height: 20rem;
          width: 100%;
          height: 100%;
          overflow: hidden;
      }
      .qx-video-player img {
        position: absolute;
        background: black;
        transition: scale 500ms cubic-bezier(0.25, 0.45, 0.45, 0.95), filter 0.6s ease;
        height: 100%;
        width: 100%;
      }
      .qx-video-player svg {
        width: 4rem;
        aspect-ratio: 1;
        position: absolute;
        left: 50%;
        top: 50%;
        translate: -50% -50%;
      }

      .qx-video-player:hover img {
        scale: 1.2;
        transition: scale 3s cubic-bezier(0.25, 0.45, 0.45, 0.95), filter 0.6s ease;
      }
      .qx-margin-remove {
        maring: 0;
      }
      .qx-text-normal {
        font-weight: normal;
      }
      .qx-text-bolder {
        font-weight: 800;
      }
      .qx-font-500 {
        font-weight: 500;
      }
      .qx-margin-small-bottom {
        margin-bottom: 10px !important;
      }
      .qx-margin-small-right {
        margin-bottom: 10px !important;
      }
      .qx-border-rounded {
        border-radius: 5px;
      }
      a[target=_blank]:before{
        display: none;
      }
  </style>
  <div id="qx-welcome-v3"  >
    <div >
      <h3 style="font-size: 2.8rem" class="qx-text-bolder qx-margin-remove" >Welcome to Quix 5</h3>
      <h4 style="font-size: 1.05rem; margin-top: 5px" class="qx-text-noraml qx-margin-small-bottom" >Experience the powerful Joomla page builder</h4>
      <div style="margin-block: 1rem 2rem;" >
        <a style="font-size: 1.05rem;" class="qx-font-500 qx-margin-small-right" href="https://www.youtube.com/@ThemeXpert/videos" target="_blank">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14.7497 17.7792C13.773 17.9165 12.4993 17.9165 10.7914 17.9165H9.20804C5.84925 17.9165 4.16987 17.9165 3.12644 16.873C2.08301 15.8296 2.08301 14.1502 2.08301 10.7915V9.20816C2.08301 5.84937 2.08301 4.16999 3.12644 3.12656C4.16987 2.08313 5.84925 2.08313 9.20804 2.08313H10.7914C14.1501 2.08313 15.8295 2.08313 16.8729 3.12656C17.9164 4.16999 17.9164 5.84937 17.9164 9.20816V10.7915C17.9164 11.7983 17.9164 12.6542 17.8882 13.3873C17.8657 13.9749 17.8545 14.2687 17.632 14.3784C17.4095 14.4882 17.1606 14.3122 16.6628 13.96L15.5414 13.1665" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.4547 10.329C12.3075 10.8513 11.6114 11.2204 10.2194 11.9585C8.87366 12.672 8.20083 13.0289 7.65859 12.8855C7.43441 12.8261 7.23016 12.7135 7.06543 12.5584C6.66699 12.1832 6.66699 11.4555 6.66699 10C6.66699 8.54462 6.66699 7.81686 7.06543 7.44166C7.23016 7.28655 7.43441 7.17394 7.65859 7.11465C8.20083 6.97124 8.87366 7.32801 10.2194 8.04156C11.6114 8.77971 12.3075 9.14879 12.4547 9.67104C12.5155 9.88662 12.5155 10.1135 12.4547 10.329Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linejoin="round"/>
          </svg>
          <span> Tutorials</span>
        </a>
        <a style="font-size: 1.05rem;" class="qx-font-500 qx-margin-small-right" href="https://www.themexpert.com/docs" target="_blank">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#fff" fill="none">
            <path d="M15 2.5V4C15 5.41421 15 6.12132 15.4393 6.56066C15.8787 7 16.5858 7 18 7H19.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4 16V8C4 5.17157 4 3.75736 4.87868 2.87868C5.75736 2 7.17157 2 10 2H14.1716C14.5803 2 14.7847 2 14.9685 2.07612C15.1522 2.15224 15.2968 2.29676 15.5858 2.58579L19.4142 6.41421C19.7032 6.70324 19.8478 6.84776 19.9239 7.03153C20 7.2153 20 7.41968 20 7.82843V16C20 18.8284 20 20.2426 19.1213 21.1213C18.2426 22 16.8284 22 14 22H10C7.17157 22 5.75736 22 4.87868 21.1213C4 20.2426 4 18.8284 4 16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M8 11H16M8 14H16M8 17H12.1708" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span> Documentation</span>
        </a>
        <a style="font-size: 1.05rem;" class="qx-font-500 qx-margin-small-right" href="https://www.themexpert.com/support" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#fff" fill="none">
              <path d="M17 13.8045C17 13.4588 17 13.286 17.052 13.132C17.2032 12.6844 17.6018 12.5108 18.0011 12.3289C18.45 12.1244 18.6744 12.0222 18.8968 12.0042C19.1493 11.9838 19.4022 12.0382 19.618 12.1593C19.9041 12.3198 20.1036 12.6249 20.3079 12.873C21.2512 14.0188 21.7229 14.5918 21.8955 15.2236C22.0348 15.7334 22.0348 16.2666 21.8955 16.7764C21.6438 17.6979 20.8485 18.4704 20.2598 19.1854C19.9587 19.5511 19.8081 19.734 19.618 19.8407C19.4022 19.9618 19.1493 20.0162 18.8968 19.9958C18.6744 19.9778 18.45 19.8756 18.0011 19.6711C17.6018 19.4892 17.2032 19.3156 17.052 18.868C17 18.714 17 18.5412 17 18.1955V13.8045Z" stroke="currentColor" stroke-width="1.5" />
              <path d="M9.5 21C10.8807 22.3333 13.1193 22.3333 14.5 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M7 13.8045C7 13.3693 6.98778 12.9782 6.63591 12.6722C6.50793 12.5609 6.33825 12.4836 5.99891 12.329C5.55001 12.1246 5.32556 12.0224 5.10316 12.0044C4.43591 11.9504 4.07692 12.4058 3.69213 12.8731C2.74875 14.0189 2.27706 14.5918 2.10446 15.2236C1.96518 15.7334 1.96518 16.2666 2.10446 16.7764C2.3562 17.6979 3.15152 18.4702 3.74021 19.1852C4.11129 19.6359 4.46577 20.0472 5.10316 19.9956C5.32556 19.9776 5.55001 19.8754 5.99891 19.6709C6.33825 19.5164 6.50793 19.4391 6.63591 19.3278C6.98778 19.0218 7 18.6307 7 18.1954V13.8045Z" stroke="currentColor" stroke-width="1.5" />
              <path d="M2 16V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12L22.0001 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span> Support</span>
        </a>
      </div>
      <a class="qx-cta-btn qx-border-rounded" href="index.php?option=com_quix&view=pages" >Get Started</a>
    </div>
    <div class="" style="position: relative; width: 45%;">
        <a href="https://www.youtube.com/watch?v=f5QniFn9lxQ" target="_blank" class="qx-video-player qx-border-rounded">
          <img src="<?php echo JUri::root(true).'/media/quixnxt/images/quix-5-builder.png' ?>" alt="Builder image preview"/>
          <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg>
        </a>
    </div>
  </div>
</div>


<script type="text/javascript">
  (() => {
    localStorage.setItem("clean-qx-cash", "true");
    localStorage.setItem("clean-qx-admin-cash", "true");
  })()
</script>
