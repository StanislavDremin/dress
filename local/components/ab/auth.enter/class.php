<?php namespace Dresscode\Main;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;

Loc::loadLanguageFile(__FILE__);

class Auth extends \CBitrixComponent
{
	private $server;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->server = Main\Context::getCurrent()->getServer();

	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	/**
	* @method getUser
	* @return \CUser
	*/
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
//		$this->arResult['BACK_URL'] = $this->server;
		$this->arResult['RND'] = $this->randString();
		if(!\CMain::IsHTTPS() && Main\Config\Option::get('main', 'use_encrypted_auth', 'N') == 'Y')
		{
			$sec = new CustomSecurity();
			if(($arKeys = $sec->LoadKeys()))
			{
				$arSafeParams = [];
				foreach($arKeys as $param)
					$arSafeParams[] = preg_replace("/[^a-z0-9_\\[\\]]/is", "", $param);

				$formId = 'system_auth_form'.$this->arResult['RND'];
				$sec->SetKeys($arKeys);
				$sec->AddToForm($formId, array('USER_PASSWORD'));

				$this->arResult['ENTER'] = array(
					"formid" => $formId,
					"key" => $sec->getProvider()->GetPublicKey(),
					"rsa_rand" => $_SESSION['__STORED_RSA_RAND'],
					"params" => $arSafeParams,
				);

				$this->arResult["SECURE_AUTH"] = true;
			}
		}

		$this->includeComponentTemplate();
	}

	private function security()
	{
		$sec = new CustomSecurity();
		$arKeys = $sec->LoadKeys();
		$sec->SetKeys($arKeys);

		return $sec->AcceptFromForm(['USER_PASSWORD']);
	}

	public function authAction($data = [])
	{
		$result = ['ok' => false, 'error' => null];

		try {

			if(!$this->userExistLogin($data['USER_EMAIL'])){
				throw new \Exception('Пользователь '.$data['USER_EMAIL'].' не найден', 404);
			}

			foreach ($data as $k => $val) {
				$_REQUEST[$k] = $val;
			}

			$resForm = $this->security();

			if($resForm == CustomSecurity::ERROR_SESS_CHECK){
				throw new \Exception('Ваша сессия истекла, повторите попытку авторизации.');
			}

			if($resForm < 0){
				throw new \Exception('Ошибка при дешифровании пароля.');
			}

			$data['USER_REMEMBER'] = $data['USER_REMEMBER'] ? 'Y' : 'N';
			$login = $this->getUser()->Login($data['USER_EMAIL'], $data['USER_PASSWORD'], $data['USER_REMEMBER']);

			if($login === true){
				$result['ok'] = true;
			} else {
				$result['error'] = ['USER_PASSWORD' => 'Неверный пароль'];
			}

		} catch (\Exception $e){
			switch ($e->getCode()){
				case 404:
					$result['error'] = ['USER_EMAIL' => $e->getMessage()];
					break;
				default:
					$result['error'] = ['ALL' => $e->getMessage()];
					break;
			}
		}

		return $result;
	}

	protected function userExistLogin($login)
	{
		$rowUser = Main\UserTable::getRow([
			'select' => [
				new Main\Entity\ExpressionField('CNT', 'COUNT(1)')
			],
			'filter' => ['=LOGIN' => $login]
		]);

		return $rowUser['CNT'] > 0;
	}


	protected function userExist($email)
	{
		$rowUser = Main\UserTable::getRow([
			'select' => [
				new Main\Entity\ExpressionField('CNT', 'COUNT(1)')
			],
			'filter' => ['=EMAIL' => $email]
		]);

		return $rowUser['CNT'] > 0;
	}

	public function registrationAction($data)
	{
		$result = ['ok' => false, 'error' => null];

		try {

			if(!check_email($data['USER_EMAIL'])){
				throw new \Exception('Введите правильный e-mail', 403);
			}

			if($this->userExist($data['USER_EMAIL'])){
				throw new \Exception('e-mail '.$data['USER_EMAIL'].' уже занят', 403);
			}

			if($data['USER_PASSWORD'] !== $data['CONFIRM_PASSWORD']){
				throw new \Exception('Пароли не совпадают', 402);
			}

			foreach ($data as $k => $val) {
				$_REQUEST[$k] = $val;
			}

			$resForm = $this->security();

			if($resForm == CustomSecurity::ERROR_SESS_CHECK){
				throw new \Exception('Ваша сессия истекла, повторите попытку авторизации.');
			}

			if($resForm < 0){
				throw new \Exception('Ошибка при дешифровании пароля.');
			}

			$CUser = new \CUser();
			$ID = $CUser->Add([
				'LOGIN' => trim($data['USER_EMAIL']),
				'NAME' => trim($data['USER_NAME']),
				'EMAIL' => trim($data['USER_EMAIL']),
				'PASSWORD' => $data['USER_PASSWORD'],
				'CONFIRM_PASSWORD' => $data['CONFIRM_PASSWORD'],
				'PERSONAL_MOBILE' => $data['USER_PHONE'],
				'GROUP_ID' => [2, 3, 4, 5],
				'TITLE' => $data['USER_NAME']
			]);
			if (intval($ID) > 0){
				$result['ok'] = true;
				$CUser->Authorize($ID, true);
			} else {
				$result['error']['ALL'] = strip_tags($CUser->LAST_ERROR);
			}

		} catch (\Exception $e){
			switch ($e->getCode()){
				case 403:
					$result['error'] = ['USER_EMAIL' => $e->getMessage()];
					break;
				case 402:
					$result['error'] = ['CONFIRM_PASSWORD' => $e->getMessage()];
					break;
				default:
					$result['error'] = ['ALL' => $e->getMessage()];
					break;
			}
		}

		return $result;
	}

	public function recoverLogin($data = [])
	{
		$result = ['ok' => false, 'error' => null];
		try {
			if(!check_email($data['USER_EMAIL'])){
				throw new \Exception('Введите правильный e-mail', 403);
			}

			if(!$this->userExist($data['USER_EMAIL'])){
				throw new \Exception('Пользователь с таким e-mail не найден', 403);
			}

			
		} catch (\Exception $err){
			if($err->getCode() == 403){
				$result['error']['USER_EMAIL'] = $err->getMessage();
			} else {
				$result['error']['ALL'] = $err->getMessage();
			}
		}

		return $result;
	}
}

class CustomSecurity extends \CRsaSecurity
{
	/**
	 * @method getProvider
	 * @return \CRsaBcmathProvider
	 */
	public function getProvider()
	{
		return $this->provider;
	}
}