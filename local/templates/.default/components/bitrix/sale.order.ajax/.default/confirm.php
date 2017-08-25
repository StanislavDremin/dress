<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
//dump($arResult);
?>

<? if (!empty($arResult["ORDER"])): ?>
	<?
	/*  ================================================= кнопка оплаты ============================================= */
	if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y'){
		if (!empty($arResult["PAYMENT"])){
			foreach ($arResult["PAYMENT"] as $payment) {
				if ($payment["PAID"] != 'Y'){
					if (!empty($arResult['PAY_SYSTEM_LIST'])
						&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
					){
						$arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];

						if (empty($arPaySystem["ERROR"])){
							?>
							<div class="pay_name"><?=Loc::getMessage("SOA_PAY")?></div>

							<div class="paysystem_name"><?=$arPaySystem["NAME"]?></div>

							<? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
								<?
								$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
								$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
								?>
								<script>
									window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
								</script>
								<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
								<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
									<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
								<? endif ?>
							<? else: ?>
								<?=$arPaySystem["BUFFERED_OUTPUT"]?>
							<? endif ?>
							<?
						} else {
							?>
							<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
							<?
						}
					} else {
						?>
						<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
						<?
					}
				}
			}
		}
	} else {?>
		<strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
	<?}

	/* ============================================================================================================== */
	?>
<? endif ?>