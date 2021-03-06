<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id: mod_random-article.php 76 2013-08-30 05:52:54Z artur.ze.alves@gmail.com $
 * @author Artur Alves
 * @copyright (C) 2012 - Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 
// Fix for Joomla 3
if (!defined('DS')) {
    define('DS',DIRECTORY_SEPARATOR);
}

require_once(dirname(__FILE__).DS.'helper.php');
require_once(dirname(__FILE__).DS.'modRandomArticle.php');

$randomArticle = new modRandomArticle();

$language = JFactory::getLanguage();
$language->load('mod_random-article');

if ($params->get('logfile')) {
    modRandomArticleHelper::logThis(1, print_r($params, true));
}

$addCurrentID = $params->get('itemid') ? true : false;
$useContentCatRouter = $params->get('contentCatUrl') ? true : false;

$numberArticles = $params->get('numberArticles');
$numberK2Articles = $params->get('numberArticlesK2');

$urls = array();
$articles = array();

try {
	$joomlaArticles = $randomArticle->getJoomlaArticles($params);
	$k2Articles = $randomArticle->getK2Articles($params);

	if ($joomlaArticles) {
		foreach ($joomlaArticles as $key => $joomlaArticle) {
			array_push($articles, $joomlaArticle);
		}
	}
	if ($k2Articles) {
		foreach ($k2Articles as $key => $k2Article) {
			array_push($articles, $k2Article);
		}
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

if (count($articles) > 0) {
    $i = 0;
    foreach($articles as $article) {
        $urls[$i] = modRandomArticleHelper::getUrl($article, $addCurrentID, $useContentCatRouter);
        
        if ($params->get('logfile')) {
            modRandomArticleHelper::logThis(2, print_r($article, true));
            modRandomArticleHelper::logThis(3, print_r($urls[$i], true));
        }
        
        $i++;
    }
}
$randomArticle->setUrls($urls);
$params->set('randomArticle', $randomArticle);

require(JModuleHelper::getLayoutPath('mod_random-article'));
?>