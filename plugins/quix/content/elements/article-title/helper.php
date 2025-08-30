<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

/**
* Joomla article element class
* instead of using direct method use class
*/
class QuixArticleTitleElement
{
    public static function getLastArticleId() {

        $db = Factory::getDbo();
        $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__content')
                    ->order('id desc')
                    ->setLimit('limit = 1')
                    ->where('state = 1');

        $db->setQuery($query);
        return $db->loadObject();
    }


    public static function getAjax($data = array())
    {
        $app       = Factory::getApplication();

        $builderMode = false;
        if(!$data){
          $data = $app->input->get('data', '', 'BASE64', 'raw');
          $data = base64_decode($data);
          if(!empty($data)){
            $builderMode = true;
          }
        }



        $params = new Registry($data);
        $itemId = self::getLastArticleId();
        $id = $app->input->get('content_id');

        if(!$id or $builderMode){
            if(!isset($itemId->id) or empty($itemId->id)) return false;
            $id = $itemId->id;
        }

        $show_date_field  = $params->get('show_date_field', 'publish_up');
        $show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');

        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

        // Get an instance of the generic articles model
        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance( 'Article', 'ContentModel', [ 'ignore_request' => true ] );
        $model->setState( 'filter.published', 1 );

        // Access filter
        $params = ComponentHelper::getParams( 'com_content' );
        $access = ! $params->get( 'show_noauth' );
        $model->setState( 'filter.access', $access );

        // Load the parameters.
        $app = Factory::getApplication('site');
        if(!$app->isClient('administrator')){
            $params = $app->getParams();
        }
        $model->setState('params', $params);

        // Retrieve Content
        $item = $model->getItem($id);
        if($item)
        {

            $item->slug    = $item->id . ':' . $item->alias;
            $item->images = json_decode($item->images);
            $item->displayDate = \Joomla\CMS\HTML\HTMLHelper::_('date', $item->$show_date_field, $show_date_format);
            $item->link = ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language);

            $item->link = \Joomla\CMS\Router\Route::_($item->link);

            $item->text = $item->introtext . ' ' . $item->fulltext;
            $item->text = \Joomla\CMS\HTML\HTMLHelper::_('content.prepare', $item->text, '', 'com_content.article');

            $item->introtext = \Joomla\CMS\HTML\HTMLHelper::_('content.prepare', $item->introtext, '', 'com_content.article');
            $item->displayIntrotext = self::_cleanIntrotext($item->introtext);

            $item->fulltext = \Joomla\CMS\HTML\HTMLHelper::_('content.prepare', $item->fulltext, '', 'com_content.article');

          Factory::getApplication()->triggerEvent('onContentPrepare', array ('com_content.article', &$item, &$params, 0));

            if ($item->catid)
            {
            $item->displayCategoryLink  = \Joomla\CMS\Router\Route::_(ContentHelperRoute::getCategoryRoute($item->catid));
            $item->displayCategoryTitle = '<a href="' . $item->displayCategoryLink . '">' . $item->category_title . '</a>';
            }
            else
            {
            $item->displayCategoryTitle = $item->category_title;
            }

            $item->displayHits       = $item->hits;
            $item->displayAuthorName = $item->author;


            $item->tags = new JHelperTags;
            $item->tags->getItemTags('com_content.article', $item->id);
        }

        return $item;
    }

    /**
   * Strips unnecessary tags from the introtext
   *
   * @param   string  $introtext  introtext to sanitize
   *
   * @return mixed|string
   *
   * @since  1.6
   */
    public static function _cleanIntrotext($introtext)
    {
        $introtext = str_replace('<p>', ' ', $introtext);
        $introtext = str_replace('</p>', ' ', $introtext);
        $introtext = strip_tags($introtext, '<a><em><strong>');
        $introtext = trim($introtext);

        return $introtext;
    }
}
