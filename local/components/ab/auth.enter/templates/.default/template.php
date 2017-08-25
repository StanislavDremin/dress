<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//$this->addExternalCss($templateFolder.'/script.css');
?>
<div id="enter-site-app">
	<div class="popUp-entrance hide_content form_site_enter" id="enter_form">
		<form @submit.prevent="onAuth" id="system_auth_form<?=$arResult['RND']?>" class="auth_form_data" method="post" name="form_auth">
			<div class="popUp-entrance__title">Авторизация</div>
			<span class="error_auth_message" v-if="getError('ALL')">{{getError('ALL')}}</span>
			<div class="popUp-entrance__form">
				<span class="lable">E-mail</span>
				<span class="error_auth_message" v-if="getError('USER_EMAIL')">{{getError('USER_EMAIL')}}</span>
				<el-input v-model="auth.USER_EMAIL" name="USER_EMAIL"></el-input>

				<span class="lable">Пароль</span>
				<span class="error_auth_message" v-if="getError('USER_PASSWORD')">{{getError('USER_PASSWORD')}}</span>
				<el-input v-model="auth.USER_PASSWORD" type="password" name="USER_PASSWORD"></el-input>

				<div class="popUp-entrance__form__desc">
					<el-checkbox-group v-model="auth.USER_REMEMBER">
						<el-checkbox label="Запомнить" name="USER_REMEMBER"></el-checkbox>
					</el-checkbox-group>

					<div class="pass-dell">
						<a href="/personal/private/?forgot_password=yes">Забыли пароль?</a>
					</div>
				</div>
				<div class="popUp-entrance__form__btn">
					<button class="btn_pink">Войти</button>
				</div>
			</div>
			<div class="popUp-entrance__line"></div>
			<div class="popUp-entrance__social">
				<div class="title">Войти через соц. сети</div>
				<div class="icons">
					<a href="javascript:"><img src="/local/dist/img/icons/form-vk.png"></a>
					<a href="javascript:"><img src="/local/dist/img/icons/form-fb.png"></a>
					<a href="javascript:"><img src="/local/dist/img/icons/form-twit.png"></a>
				</div>
			</div>
			<div class="popUp-entrance__desc">
				<span>Впервые здесь?</span>
				<a href="javascript:" @click.prevent="showRegisterForm('#register_form')">Зарегистрируйтесь</a>
			</div>
		</form>
	</div>

	<!-- ========================================== begin регистрация ============================================== -->
	<div class="popUp-sign-up hide_content" id="register_form">
		<div class="popUp-sign-up__title">Регистрация</div>
		<div class="popUp-sign-up__form">
			<span class="error_auth_message" v-if="registerErrors.ALL">{{registerErrors.ALL}}</span>
			<form @submit.prevent="" name="form_register" class="auth_form_data" method="post" autocomplete="off">
				<span class="lable">Контактное лицо</span>
				<div class="el-input">
					<input type="text" class="el-input__inner" v-model="register.USER_NAME" data-vv-as="ФИО" name="USER_NAME" v-validate="'required'" />
					<error-wrap :show="errors.has('USER_NAME')">{{ errors.first('USER_NAME') }}</error-wrap>
				</div>

				<span class="lable">Номер телефона</span>
				<div class="el-input">
					<mask-input type="text" class="el-input__inner"
							v-model="register.USER_PHONE"  data-vv-as="Телефон" v-validate="'required'"
							data-vv-value-path="innerValue" data-vv-name="custom"
							name="USER_PHONE" mask="\+\7(111)111-11-11" placeholder="+7(___)___-__-__" />
					<error-wrap :show="errors.has('USER_PHONE')">{{ errors.first('USER_PHONE') }}</error-wrap>
				</div>

				<span class="lable">E-mail</span>
				<div class="el-input">
					<input type="text" class="el-input__inner" v-model="register.USER_EMAIL" data-vv-as="E-mail"
							name="USER_EMAIL" v-validate="'email'" />
					<error-wrap :show="errors.has('USER_EMAIL')">{{ errors.first('USER_EMAIL') }}</error-wrap>
				</div>

				<span class="lable">Пароль</span>
				<div class="el-input">
					<input type="password" autocomplete="off" class="el-input__inner" data-vv-as="Пароль" v-model="register.USER_PASSWORD"
							name="USER_PASSWORD" v-validate="'required|min:6'"/>
					<error-wrap :show="errors.has('USER_PASSWORD')">{{ errors.first('USER_PASSWORD') }}</error-wrap>
				</div>

				<span class="lable">Подтвердить пароль</span>
				<div class="el-input">
					<input type="password" autocomplete="off" class="el-input__inner" data-vv-as="Подтверждение пароля"
							v-model="register.CONFIRM_PASSWORD" name="CONFIRM_PASSWORD" v-validate="'required|min:6'"/>
					<error-wrap style="top: -35px;" :show="errors.has('CONFIRM_PASSWORD')">{{ errors.first('CONFIRM_PASSWORD') }}</error-wrap>
				</div>

				<div class="popUp-sign-up__form__btn" v-if="!registered">
					<a class="btn_pink" @click="sendRegistration" href="javascript:">Зарегистрироваться</a>
				</div>
			</form>
		</div>
		<div class="popUp-sign-up__line"></div>
		<div class="popUp-sign-up__social">
			<div class="title">Регистрация через соц. сети</div>
			<div class="icons">
				<a href="javascript:"><img src="/local/dist/img/icons/form-vk.png"></a>
				<a href="javascript:"><img src="/local/dist/img/icons/form-fb.png"></a>
				<a href="javascript:"><img src="/local/dist/img/icons/form-twit.png"></a>
			</div>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->


	<?/*
	<!--begin popUp-pass-dell-->
	<div class="popUp-pass-dell hide_content" id="recover_pass">
		<div class="popUp-pass-dell__title">Восстановления пароля</div>
		<div class="popUp-pass-dell__form" id="app-input2">
			<div class="title">Укажите e-mail к которому <br /> привязан ваш аккаунт</div>
			<form @submit.prevent="" name="form_recover_pass" class="auth_form_data" method="post" autocomplete="off">

				<span class="lable">E-mail</span>
				<el-input  data-vv-as="E-mail" name="USER_EMAIL" v-validate="'email'" v-model="recover.USER_EMAIL"></el-input>
				<error-wrap style="top: auto;margin-top: -95px; left: 3%;"
						:show="errors.has('USER_EMAIL')">{{ errors.first('USER_EMAIL') }}</error-wrap>
				<div class="popUp-sign-up__form__btn">
					<a class="btn_pink" href="javascript:" @click.prevent="sendRecoverLogin">Восстановить</a>
				</div>
			</form>
		</div>
		<div class="popUp-pass-dell__line"></div>
		<div class="popUp-pass-dell__desc">
			На указаную почту будет высланно <br />
			письмо с ссылкой, пройдя по которой <br />
			Вы сможете сменить пароль.
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
	*/?>
</div>
<script>
	$(function () {
		AuthEnter.$data.enter = <?=CUtil::PhpToJSObject($arResult['ENTER'])?>;
		AuthEnter.$mount('#enter-site-app');
	});
</script>