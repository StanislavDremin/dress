<template>
	<div class="form_items" v-loading="processOrder">
		<div class="form_groups">
			<label>ФИО</label>
			<el-input name="fio" v-model="order.fio" data-vv-as="ФИО" v-validate="'required'"
					data-vv-value-path="innerValue" data-vv-name="fio"/>
			<error-wrap :show="errors.has('fio')">{{ errors.first('fio') }}</error-wrap>
		</div>
		<div class="form_groups">
			<label>E-mail</label>
			<el-input name="email" v-model="order.email" data-vv-as="E-mail" v-validate="'required|email'"
					data-vv-value-path="innerValue" data-vv-name="email" />
			<error-wrap :show="errors.has('email')">{{ errors.first('email') }}</error-wrap>
		</div>
		<div class="form_groups">
			<label>Телефон</label>
			<mask-input type="text" class="el-input__inner"
					v-model="order.phone" data-vv-as="Телефон" v-validate="'required'"
					data-vv-value-path="innerValue" data-vv-name="phone"
					name="phone" mask="\+\7(111)111-11-11" placeholder="+7(___)___-__-__" />
			<error-wrap :show="errors.has('phone')">{{ errors.first('phone') }}</error-wrap>
		</div>
		<div class="form_groups">
			<label>Комментарий</label>
			<el-input type="textarea" v-model="order.comment" :rows="5" />
		</div>
		<div class="form_groups btn_groups">
			<a href="javascript:" class="btn_pink" @click.prevent="simpleOrderSave">Оформить заказ</a>
		</div>
	</div>
</template>
<script>
	import Validate, { Validator } from "vee-validate";
	import ru from "vee-validate/dist/locale/ru";
	import MaskInput from 'vue-masked-input';
	import ErrorWrap from 'validator/ErrorWrap.vue';
	Validator.addLocale(ru);
	
	export default {
		props: {
			productId: {type: Number|String}
		},
		data() {
			return {
				order: {
					fio: 'test',
					email: 'testse@asdasd.ru',
					phone: '',
					comment: 'test',
				},
				processOrder: false
			}
		},
		methods: {
			simpleOrderSave(){
				this.$validator.validateAll().then(result => {
					if(result){
						let post = this.order;
						post.productId = this.productId;
						this.processOrder = true;
						
						this.$http.post('/rest/orderCreate/saveOrderSimple', post).then(res => {
							if(res.data.STATUS === 1){
								this.$emit('saveOrderSimple');
							}else if(res.data.ERRORS !== null) {
								swal('', res.data.ERRORS.join(', '), 'error');
							}
							this.processOrder = false;
						});
					}
				});
			}
		},
		watch: {},
		created() {
		},
		beforeUpdate() {
		},
		components: {
			ErrorWrap, MaskInput
		}
	}
</script>
<style>
	.form_items .form_groups {
		margin-bottom: 17px;
	}
	.form_groups.btn_groups {
		text-align: center;
		padding-top: 10px;
	}
	.form_groups .error_field_wrap {
		margin-top: -64px;
	}
</style>