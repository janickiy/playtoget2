<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Rss
{
	static function addNews($arrs) 
	{
		if(is_array($arrs)){

			core::database()->delete(core::database()->getTableName('news_rss_sport'));
		
			for($i=0; $i<count($arrs['link']); $i++){
				$fields = array();
				$fields['id'] = 0;
				$fields['title'] = $arrs['title'][$i];
				$fields['link'] = $arrs['link'][$i];
				$fields['description'] = $arrs['description'][$i];
				$fields['pubdate'] = date('Y-m-d H:i:s', strtotime($arrs['pubdate'][$i]));
				
				core::database()->insert($fields, core::database()->getTableName('news_rss_sport'));				
			}
			
			return TRUE;
		}
		else return FALSE;
	}
	
	static function getRssNews($limit = 5)
	{
		$query = "SELECT * FROM " . core::database()->getTableName('news_rss_sport') . " ORDER BY id DESC LIMIT " . $limit . "";
		$result = core::database()->querySQL($query);
		
		return core::database()->getColumnArray($result);
	}	
}