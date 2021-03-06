<?php

/**
* Grid Title Overlay
* @package News Show Pro GK5
* @Copyright (C) 2009-2013 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @version $Revision: GK5 1.3.3 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');

class NSP_GK5_Highlights {
	// necessary class fields
	private $parent;
	private $mode;
	// constructor
	function __construct($parent) {
		$this->parent = $parent;
		// detect the supported Data Sources
		if(stripos($this->parent->config['data_source'], 'com_content_') !== FALSE) {
			$this->mode = 'com_content';
		} else if(stripos($this->parent->config['data_source'], 'k2_') !== FALSE) { 
			$this->mode = 'com_k2';
		} else if(stripos($this->parent->config['data_source'], 'easyblog_') !== FALSE) { 
			$this->mode = 'com_easyblog';
		} else {
			$this->mode = false;
		}
	}
	// static function which returns amount of articles to render - VERY IMPORTANT!!
	static function amount_of_articles($parent) {
		return $parent->config['portal_mode_highlights_amount'];
	}
	// output generator	
	function output() {	
		// output the HTML code
		echo '<ol class="gkNspPM gkNspPM-Highlights">';
		// render blocks
		for($i = 0; $i < count($this->parent->content); $i++) {
			echo '<li>';
				echo '<h3><a href="'.$this->get_link($i).'" title="'.htmlspecialchars(strip_tags($this->parent->content[$i]['title'])).'">'.NSP_GK5_Utils::cutText(htmlspecialchars($this->parent->content[$i]['title']), $this->parent->config, 'portal_mode_highlights_title_limit', '&hellip;').'</a></h3>';
			
				if($this->parent->config['portal_mode_highlights_date_format'] != '') {
					echo '<p>' . JHTML::_('date', $this->parent->content[$i]['date'], $this->parent->config['portal_mode_highlights_date_format']) . '</p>';
				}
			echo '</li>';
		}
		echo '</ol>';
		if($this->parent->config['portal_mode_highlights_readmore'] != '') {
			echo '<a href="'.$this->parent->config['portal_mode_highlights_readmore'].'">'.JText::_('MOD_NEWS_PRO_GK5_PORTAL_MODE_HIGHLIGHTS_READMORE_VALUE').'</a>';
		}
	}
	// function used to retrieve the item URL
	function get_link($num) {
		if($this->mode == 'com_content') {
			return ($this->parent->content[$num]['id'] != 0) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->parent->content[$num]['id'], $this->parent->content[$num]['cid'])) : JRoute::_('index.php?option=com_users&view=login');
		} else if($this->mode == 'com_k2') {
			//
			require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
			//
			return urldecode(JRoute::_(K2HelperRoute::getItemRoute($this->parent->content[$num]['id'].':'.urlencode($this->parent->content[$num]['alias']), $this->parent->content[$num]['cid'].':'.urlencode($this->parent->content[$num]['cat_alias']))));
		} else if($this->mode == 'com_easyblog') {
			//
			require_once (JPATH_SITE.DS.'components'.DS.'com_easyblog'.DS.'helpers'.DS.'router.php');
			//
			return urldecode(JRoute::_(EasyBlogRouter::getEntryRoute($this->parent->content[$num]['id'])));
		} else {
			return false;
		}
	}
}

// EOF