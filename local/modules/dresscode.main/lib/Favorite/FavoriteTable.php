<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 10.08.2017
 */

namespace Dresscode\Main\Favorite;

use AB\Tools\Helpers\MainDataManager;
use Bitrix\Main;

Main\Loader::includeModule('sale');

class FavoriteTable extends MainDataManager
{

	protected static function getIndexes()
	{
		return [
			'dix_favorite_element' => ['ELEMENT_ID', 'FUSER_ID'],
			'dix_favorite_user' => ['FUSER_ID']
		];
	}

	public static function getTableName()
	{
		return 'd_favorite';
	}

	public static function getMap()
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'ELEMENT_ID' => new Main\Entity\IntegerField('ELEMENT_ID', ['required' => true]),
			'USER_ID' => new Main\Entity\IntegerField('USER_ID', [
				'default_value' => self::getUser()->GetID()
			]),
			'DATE_CREATE' => new Main\Entity\DatetimeField('DATE_CREATE', [
				'default_value' => new Main\Type\DateTime()
			]),
			'DATE_UPDATE' => new Main\Entity\DatetimeField('DATE_UPDATE', [
				'default_value' => new Main\Type\DateTime()
			]),
			'MODULE_ID' => new Main\Entity\StringField('MODULE_ID', [
				'default_value' => 'iblock'
			]),
			'PROPS' => new Main\Entity\TextField('PROPS', [
				'serialized' => true
			]),
			'FUSER_ID' => new Main\Entity\IntegerField('FUSER_ID', [
				'default_value' => \CSaleBasket::GetBasketUserID()
			])
		];
	}

	/**
	 * @method getByElement
	 * @param $elementId
	 *
	 * @return array|null
	 * @throws \Exception
	 */
	public static function getByElement($elementId)
	{
		$elementId = (int)$elementId;
		if($elementId == 0){
			throw new \Exception('element id is null', 500);
		}

		return parent::getRow([
			'filter' => ['=ELEMENT_ID' => $elementId, '=FUSER_ID' => \CSaleBasket::GetBasketUserID()],
		]);
	}

}