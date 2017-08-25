<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 19.07.2017
 */

namespace Dresscode\Main\EventHandlers;


use Bitrix\Main\Data\TaggedCache;
use Dresscode\Main\Config;

class IblockData
{
	public static function clearSectionMenuCache()
	{
		$tag = new TaggedCache();

		$tag->clearByTag(Config::TOP_MENU_CACHE);
		$tag->clearByTag(Config::LEFT_MENU_CACHE);
	}
}