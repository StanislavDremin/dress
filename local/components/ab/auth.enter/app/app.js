/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import { ElForm, ElFormItem, ElCheckbox, ElInput } from 'element-ui';
import Validate, { Validator } from "vee-validate";
import ru from "vee-validate/dist/locale/ru";
import MaskInput from 'vue-masked-input';

Validator.addLocale(ru);
Vue.use(Validate, {
	locale: 'ru',
});

const ErrorWrap = {
	template: `
		<span v-show="show" class="error_field_wrap animated slideInUp">
			<span class="error_field">
				<slot></slot>
				<i class="fa fa-caret-down"></i>
			</span>
		</span>
	`,
	props: {
		show: {
			type: Boolean,
			default: false
		},
		effect:{
			type: String,
			default: 'slideInUp'
		},
	}
};

window.AuthEnter = new Vue({
	data: () => ({
		auth: {
			USER_EMAIL: '',
			USER_PASSWORD: '',
			USER_REMEMBER: '',
			__RSA_DATA: ''
		},
		register: {
			USER_EMAIL: '',
			USER_PASSWORD: '',
			CONFIRM_PASSWORD: '',
			USER_NAME: '',
			USER_PHONE:'',
			__RSA_DATA: ''
		},
		recover: {
			USER_EMAIL: ''
		},
		enter: {},
		restOptions: {
			baseURL: '/rest/s/auth'
		},
		authErrors: {},
		registerErrors: {},
		registered: false
	}),
	methods: {
		_addRsa(name = 'auth'){
			if (top.hasOwnProperty('rsasec_form')) {
				top.rsasec_form(this.enter);
			}

			if(this.hasOwnProperty(name)){
				this[name].__RSA_DATA = $('[name=__RSA_DATA]').val();
			}
		},
		onAuth() {
			this._addRsa('auth');

			this.rest().post('/auth', this.auth).then(res => {
				if (res.data.STATUS === 1) {
					if (res.data.DATA.ok === false) {
						if (res.data.DATA.error !== null) {
							this.authErrors = res.data.DATA.error;
						} else {
							this.authErrors = {ALL: 'Неверный логин или пароль.'}
						}
					} else {
						window.location.reload(true);
					}
				}
			});
		},
		hasErrors(){
			return this.$count(this.authErrors) > 0;
		},
		getError(code = 'ALL'){
			return this.authErrors[code];
		},
		showRegisterForm(name){
			const instance = window.$.fancybox.getInstance();
			instance.close();

			window.$.fancybox.open({
				src: name,
				type : 'inline',
			});

			this.errors.clear();
		},
		sendRegistration(){
			this.$validator.validateAll().then(result => {
				if (result) {
					this._addRsa('register');

					this.rest().post('/registration', this.register).then(res => {
						if (res.data.STATUS === 1) {
							if (res.data.DATA.ok === false) {
								if (res.data.DATA.error !== null) {
									this.registerErrors = res.data.DATA.error;
								} else {
									this.registerErrors = {ALL: 'Ошибка регистрации'}
								}
							} else {
								this.registered = true;
								window.location.reload(true);
							}
						}
					});
				}
			});
		},
		sendRecoverLogin(){
			this.$validator.validateAll().then(result => {
				if(result){
				}
			});
		}
	},
	computed: {
		isFormValid() {
			let valid = false,
				validCount = 0,
				arKeys = Object.keys(this.fields);

			arKeys.some(key => {
				if (this.fields[key].valid === true) {
					validCount++;
				}
			});
			if (arKeys.length === validCount) {
				valid = true;
			}

			return valid;
		},
	},
	watch: {
		registerErrors(value){

			this.$foreach(value, (err, code) => {
				this.errors.errors.push({
					field:code,
					msg:err,
					rule:"required",
					scope:"__global__",
				})
			});
		}
	},
	components: {
		ErrorWrap,
		MaskInput
	}
});