<?php
/**
 * ================================================================================
 * Backup copy of Joomla's configuration.php
 * ================================================================================
 *
 * Below you can find the contents of the configuration.php file which was read by
 * the Akeeba Backup Restoration Script when it initialised.
 *
 * This is NOT necessarily the contents of your backed up site's configuration.php
 * file. Every time you run the Restoration Script past the Site Setup page, the
 * configuration.php file gets modified. If you want to reset and start over after
 * going past the Site Setup page you'll need to extract your backup archive again. 
 */?><?php
class JConfig {
	public $offline = false;
	public $offline_message = 'Hier entsteht die Website Beefmaster Austria.<br />Unter dieser Email können sie bereits Kontakt it uns aufnehmen: contact@beefmaster.at ';
	public $display_offline_message = 1;
	public $offline_image = 'images/favicon.png#joomlaImage://local-images/favicon.png?width=64&height=64';
	public $sitename = 'Beefmaster Austria -  Zucht, Verkauf, Beratung';
	public $editor = 'tinymce';
	public $captcha = 'jvmath';
	public $list_limit = 20;
	public $access = 1;
	public $debug = false;
	public $debug_lang = false;
	public $debug_lang_const = true;
	public $dbtype = 'mysqli';
	public $host = 'mysqlsvr84.world4you.com';
	public $user = 'sql8328283';
	public $password = 'byve3x+u';
	public $db = '6212466db2';
	public $dbprefix = 'bma_';
	public $dbencryption = 0;
	public $dbsslverifyservercert = false;
	public $dbsslkey = '';
	public $dbsslcert = '';
	public $dbsslca = '';
	public $dbsslcipher = '';
	public $force_ssl = 0;
	public $live_site = '';
	public $secret = 'giQxxUGZQB8FJxNF';
	public $gzip = true;
	public $error_reporting = 'none';
	public $helpurl = 'https://help.joomla.org/proxy?keyref=Help{major}{minor}:{keyref}&lang={langcode}';
	public $offset = 'Europe/Vienna';
	public $mailonline = true;
	public $mailer = 'mail';
	public $mailfrom = 'contact@beefmaster.at';
	public $fromname = 'Beefmaster Austria';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = false;
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'localhost';
	public $smtpsecure = 'none';
	public $smtpport = 25;
	public $caching = 0;
	public $cache_handler = 'file';
	public $cachetime = 15;
	public $cache_platformprefix = false;
	public $MetaDesc = 'Erster Beefmaster Zucht Betrieb Österreichs. 
✓ Zuchtiere, ✓ Beratung, ✓Support 
Profitabel durch die richtige Wahl der Rasse! ';
	public $MetaAuthor = true;
	public $MetaVersion = false;
	public $robots = '';
	public $sef = true;
	public $sef_rewrite = false;
	public $sef_suffix = false;
	public $unicodeslugs = false;
	public $feed_limit = 10;
	public $feed_email = 'none';
	public $log_path = '/home/.sites/88/site6212466/web/administrator/logs';
	public $tmp_path = '/home/.sites/88/site6212466/web/tmp';
	public $lifetime = 999;
	public $session_handler = 'database';
	public $shared_session = false;
	public $session_metadata = true;
	public $memcached_persist = true;
	public $memcached_compress = false;
	public $memcached_server_host = 'localhost';
	public $memcached_server_port = 11211;
	public $redis_persist = true;
	public $redis_server_host = 'localhost';
	public $redis_server_port = 6379;
	public $redis_server_db = 0;
	public $cors = false;
	public $cors_allow_origin = '*';
	public $cors_allow_headers = 'Content-Type,X-Joomla-Token';
	public $cors_allow_methods = '';
	public $behind_loadbalancer = false;
	public $proxy_enable = false;
	public $proxy_host = '';
	public $proxy_port = '';
	public $proxy_user = '';
	public $massmailoff = true;
	public $replyto = '';
	public $replytoname = '';
	public $MetaRights = '';
	public $sitename_pagetitles = 0;
	public $session_filesystem_path = '';
	public $session_memcached_server_host = 'localhost';
	public $session_memcached_server_port = 11211;
	public $session_redis_persist = 1;
	public $session_redis_server_host = 'localhost';
	public $session_redis_server_port = 6379;
	public $session_redis_server_db = 0;
	public $frontediting = 0;
	public $block_floc = 1;
	public $log_everything = 0;
	public $log_deprecated = 0;
	public $log_priorities = array('0' => 'all');
	public $log_categories = '';
	public $log_category_mode = 0;
	public $cookie_domain = '';
	public $cookie_path = '';
	public $asset_id = '1';
	public $redis_server_auth = '';
	public $session_redis_server_auth = '';
	public $session_metadata_for_guest = false;
}