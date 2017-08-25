<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 21.07.2017
 */

namespace Dresscode\Main;

use Bitrix\Main\Entity;
use Bitrix\Main\Application;

class FileTable extends \Bitrix\Main\FileTable
{
	public static function getMap()
	{
		$map = parent::getMap();
		$connection = Application::getConnection();
		$helper = $connection->getSqlHelper();
		$map['SRC'] = new Entity\ExpressionField(
			'SRC',
			$helper->getConcatFunction("'/upload/'", "%s", "'/'", "%s"),
			['SUBDIR', 'ORIGINAL_NAME']
		);
		$map['FORMAT_SIZE'] = new Entity\ExpressionField(
			'FORMAT_SIZE',
			'%s',
			['FILE_SIZE'],
			['fetch_data_modification' => function () {
				return array(
					function ($value) {
						return \CFile::FormatSize($value);
					}
				);
			}]
		);

		return $map;
	}

}