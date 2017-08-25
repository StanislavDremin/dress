<template>
	<div>
		<form @submit.prevent="">
			<div class="basket-sidebar">
				
				<div class="basket-sidebar__title basket-title">Оформление заказа</div>
				
				<div class="basket-sidebar__form">
					
					<div class="basket_form_group">
						<span>Номер телефона</span>
						<mask-input type="text" class="el-input__inner"
								v-model="order.phone" data-vv-as="Телефон" v-validate="'required'"
								data-vv-value-path="innerValue" data-vv-name="ORDER_PHONE"
								name="ORDER_PHONE" mask="\+\7(111)111-11-11" placeholder="+7(___)___-__-__" />
						<error-wrap :show="errors.has('ORDER_PHONE')">{{ errors.first('ORDER_PHONE') }}</error-wrap>
					</div>
					<div class="basket_form_group">
						<span>Способ доставки</span>
						<el-select v-model="deliveryItem" placeholder="Выбрать" data-vv-value-path="innerValue"
								data-vv-as="Доставка" data-vv-name="ORDER_DELIVERY" name="ORDER_DELIVERY" v-validate="'required'">
							<el-option v-for="item in deliveryList" :key="item.ID" :label="item.NAME" :value="item.ID"></el-option>
						</el-select>
						<error-wrap :show="errors.has('ORDER_DELIVERY')">{{ errors.first('ORDER_DELIVERY') }}</error-wrap>
					</div>
					
					<transition name="custom-classes-transition"
							enter-active-class="animated fadeInDown"
							leave-active-class="animated fadeOut">
					
						<div class="delivery_city" v-if="deliveryItem != 3 && deliveryItem !== null">
							<div class="basket_form_group">
								<span>Регион</span>
								<el-select v-model="order.region" filterable remote placeholder="Введите регион"
										debounce="300" :remote-method="changeRegion"
										:loading="loadingLocation" loading-text="Поиск...">
									<el-option
											v-for="item in store.regions"
											:key="item.value"
											:label="item.label"
											:value="item.value">
									</el-option>
								</el-select>
								<!--<el-input v-model="order.region" @input="changeRegion"/>-->
							</div>
							
							<transition name="custom-classes-transition"
									enter-active-class="animated fadeInLeft"
									leave-active-class="animated fadeOut">
								
								<div class="basket_form_group" v-if="order.region.length > 0">
									<span>Город</span>
									<el-select v-model="order.city" filterable remote placeholder="Введите город"
											debounce="300" :remote-method="changeCity"
											:loading="loadingLocation" loading-text="Поиск...">
										<el-option
												v-for="item in store.cities"
												:key="item.value"
												:label="item.label"
												:value="item.value">
										</el-option>
									</el-select>
								</div>
							</transition>
							
							<transition name="custom-classes-transition"
									enter-active-class="animated fadeInLeft"
									leave-active-class="animated fadeOut">
								
								<div class="basket_form_group" v-if="order.city.length > 0">
									<span>Улица</span>
									<el-select v-model="order.street" filterable remote placeholder="Введите улицу"
											debounce="300" :remote-method="getStreet"
											:loading="loadingLocation" loading-text="Поиск...">
										<el-option
												v-for="item in store.streets"
												:key="item.value"
												:label="item.label"
												:value="item.label">
										</el-option>
									</el-select>
								</div>
							</transition>
							<transition name="custom-classes-transition"
									enter-active-class="animated fadeInLeft"
									leave-active-class="animated fadeOut">
								
								<div class="basket_form_group horizontal_items" v-if="order.city.length > 0">
									<div class="line_item">
										<span>Дом</span>
										<el-input v-model="order.house"/>
									</div>
									<div class="line_item">
										<span>Квартира</span>
										<el-input v-model="order.apartment"/>
									</div>
								</div>
							</transition>
						</div>
						
					</transition>
					
					<div class="basket_form_group">
						<span>Контактное лицо (ФИО)</span>
						<el-input placeholder="" v-model="order.fio" name="ORDER_FIO" data-vv-as="ФИО"
								data-vv-value-path="innerValue" data-vv-name="ORDER_FIO" v-validate="'required'"></el-input>
						<error-wrap :show="errors.has('ORDER_FIO')">{{ errors.first('ORDER_FIO') }}</error-wrap>
					</div>
					
					<div class="basket_form_group">
						<span>E-mail</span>
						<el-input v-model="order.email" name="ORDER_EMAIL" data-vv-as="E-mail"
								data-vv-value-path="innerValue" data-vv-name="ORDER_EMAIL" v-validate="'required|email'"></el-input>
						<error-wrap :show="errors.has('ORDER_EMAIL')">{{ errors.first('ORDER_EMAIL') }}</error-wrap>
					</div>
				</div>
				
				<div class="basket-sidebar__desc">
					
					<div class="totals-count">
						<span class="count">Количество товаров: {{ totalCount }} шт.</span>
						<span class="count" v-if="deliveryPrice.price != 0">Доставка: {{ deliveryPrice.format }}</span>
						<span class="total">Итого: {{ getOrderPrice() }} руб.</span>
					</div>
					
					<div class="promo-sale">
						<div class="promo">
							<input type="text" placeholder="Промокод">
							<button type="button">Применить</button>
						</div>
						<!--<div class="sale">
							<span class="sale-promo">Скидка: 3 600 руб.</span>
							<span class="total-promo">Итого: 59 379 руб.</span>
						</div>-->
					</div>
					
					<div class="offers">
						<p>Нажимая на кнопку «Отправить заказ»,<br>
							вы принимаете условия <br>
							<a href="#">Публичной оферты</a>
						</p>
					</div>
					
					<div class="btn-full">
						<a class="btn_pink" href="javascript:" @click="payments">Отправить заказ</a>
					</div>
				
				</div>
			
			</div>
		</form>
		
		<detail-modal :show="showPays" @onModalClose="showPays = false" class-name="pay_system_wrap">
			<div class="basket_pays_wrap" slot="m_body" v-loading="orderProcess" element-loading-text="Создание заказа...">
				<div class="left_side">
					<h2>{{order.fio}}, выберите способ оплаты</h2>
					<div class="pays_items">
						<div class="pay_item" v-for="item in paySystems" :key="item.ID" @click="paySelected = item.ID">
							<span class="pay_item_name">
								{{ item.PSA_NAME }}
								<span class="pay_item_desc" v-html="item.DESCRIPTION"></span>
							</span>
							<span class="radio_item">
								<span :class="['inner_ring', {'active': item.ID === paySelected}]"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="right_side">
					<div style="flex: 1">
						<div class="right_info">
							<p>Количество товара: {{ totalCount }} шт.</p>
							<p>Доставка: {{ deliveryPrice.format }}</p>
						</div>
						<div class="right_itog">
							<b>Итого к оплате: {{ getOrderPrice() }}</b>
						</div>
					</div>
					<div class="btn_save_order" v-if="paySelected !== null">
						<a href="javascript:" @click.prevent="saveOrder" class="btn_pink">Оплатить</a>
					</div>
				</div>
			</div>
		</detail-modal>
	</div>
</template>

<script>
	import Actions from './ActionApi';
	import Validate, { Validator } from "vee-validate";
	import ru from "vee-validate/dist/locale/ru";
	import MaskInput from 'vue-masked-input';
	import ErrorWrap from 'validator/ErrorWrap.vue';
	import DetailModal from 'DetailModal.vue';

	Validator.addLocale(ru);
	
	export default {
		props: {
			BasketItems: {type: Array, default: []},
			totalSum: {type: Number|String, default: 0},
			totalSumFormat: {type: String, default: ''},
			totalCount: {type: Number|String, default: 0},
		},
		data() {
			return {
				order: {
					phone: '',
					fio: '',
					email: '',
					region: '',
					city: '',
					street: '',
					house: '',
					apartment: ''
				},
				deliveryList: [{
					value: null,
					label: 'Выбрать'
				}],
				deliveryItem: null,
				Rest: this.$resource('', {}, Actions),
				store: {
					regions: [],
					cities: [],
					streets: []
				},
				loadingLocation: false,
				showPays: false,
				paySystems: [],
				paySelected: null,
				deliveryPrice: {price: 0, format: '0 руб.'},
				orderProcess: false,
			}
		},
		beforeUpdate(data){
		},
		created() {
			this.Rest.getDelivery().then(res => {
				if(res.data.STATUS === 1){
					this.deliveryList = res.data.DATA;
				}
			});
		},
		computed: {},
		components: {
			ErrorWrap,
			MaskInput,
			DetailModal
		},
		watch: {
			deliveryItem(id){
				if(id !== null){
					let priceDeliveryItem = this.deliveryList.filter((el) => {
						return el.ID === id;
					}).shift();

					if(priceDeliveryItem instanceof Object){
						this.deliveryPrice = {
							price: priceDeliveryItem.PRICE,
							format: this.$formatPrice(priceDeliveryItem.PRICE) + ' руб.'
						};
					}
				}
			}
		},
		methods: {
			changeRegion(query){
				if (query !== '' && query.length > 2) {
					this.loadingLocation = true;
					this.Rest.searchLocation({query, type: 'region'}).then(res => {
						if(res.data.STATUS === 1){
							this.store.regions = res.data.DATA;
						}
						this.loadingLocation = false;
					});
				} else {
					this.order.region = '';
				}
			},
			changeCity(query){
				if (query !== '' && query.length > 2) {
					this.loadingLocation = true;
					this.Rest.searchLocation({query, type: 'city'}).then(res => {
						if(res.data.STATUS === 1){
							this.store.cities = res.data.DATA;
						}
						this.loadingLocation = false;
					});
				} else {
					this.order.city = '';
				}
			},

			getStreet(query){
				if (query !== '' && query.length > 2) {
					this.loadingLocation = true;
					
					let city = this.store.cities.filter((el) => {
						return this.order.city === el.value;
					}).shift();
					
					this.Rest.getStreet({query, city: city.label}).then(res => {
						if(res.data.STATUS === 1){
							this.store.streets = res.data.DATA;
						}
						this.loadingLocation = false;
					});
				} else {
					this.order.street = '';
				}
			},
			
			saveOrder(){
				this.orderProcess = true;
				let post = Object.assign({}, this.order);
				post.delivery = this.deliveryItem;
				post.paySelected = this.paySelected;
				this.Rest.saveOrder(post).then(res => {
					if(res.data.STATUS === 1 && res.data.DATA !== null){
						window.location.assign('/personal/order/make/?ORDER_ID='+ res.data.DATA);
					}
					
					this.orderProcess = false;
				});
			},
			payments(){
				this.$validator.validateAll().then(result => {
					if (result) {
						let post = Object.assign({}, this.order);
						post.delivery = this.deliveryItem;

						this.Rest.getPayments(post).then(res => {
							if(res.data.STATUS === 1){
								this.paySystems = res.data.DATA;
								this.showPays = true;
							}
						})
					}
				});
			},
			getOrderPrice() {
				let sum = this.$intVal(this.totalSum) + this.$intVal(this.deliveryPrice.price);
				return this.$formatPrice(sum) + ' руб.';
			},
			
			
		},
		
	}
</script>
<style>
	.basket-sidebar__form .error_field_wrap {
		margin-top: 10px;
	}
	.basket_form_group {
		display: flex;
		flex-direction: column;
		margin-bottom: 20px;
	}
	.basket-sidebar__form .animated {
		-webkit-animation-duration: .3s;
		-o-animation-duration: .3s;
		-moz-animation-duration: .3s;
		animation-duration: .3s;
	}
	.basket_form_group.horizontal_items {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}
	.basket_form_group.horizontal_items .line_item {
		width: 45%;
	}
	.pay_system_wrap .modal_data_wrap {
		width: 768px !important;
		top: 15%;
	}
	.basket_pays_wrap {
		display: flex;
	}
	.basket_pays_wrap .left_side,
	.basket_pays_wrap .right_side {
		margin: 0; padding: 25px;
	}
	.basket_pays_wrap .left_side {
		flex: 8;
	}
	.basket_pays_wrap .right_side {
		flex: 5;
		display: flex;
		flex-direction: column;
		align-content: space-around;
	}
	.basket_pays_wrap .right_side {
		background: #FAF7EC;
		border-radius: 0 5px 5px 0;
		border: 1px solid #FAF7EC;
	}
	.basket_pays_wrap .right_side .right_info {
		padding-bottom: 25px;
		border-bottom: 1px solid #DEE5EC;
		margin-bottom: 20px;
	}
	
	.pay_system_wrap .header_modal,
	.pay_system_wrap .body_modal,
	.pay_system_wrap .footer_modal {
		height: 0;
		padding: 0;
	}
	
	.basket_pays_wrap {
		min-height: 300px;
		background: #fff;
	}
	
	.left_side h2 {
		line-height: 28px;
	}
	
	.pays_items {
		margin-top: 15px;
	}
	.pays_items .pay_item {
		padding: 15px 0;
		border-bottom: 1px solid #DEE5EC;
		display: flex;
		justify-content: space-between;
		cursor: pointer;
	}
	.radio_item {
		width: 25px;
		height: 25px;
		border: 1px solid #DEE5EC;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.radio_item .inner_ring {
		width: 11px;
		height: 11px;
		background: transparent;
		border-radius: 50%;
	}
	.pays_items .pay_item:hover .inner_ring,
	.inner_ring.active {
		background: #EC7575;
	}
	.pay_item_desc {
		display: block;
		clear: both;
		margin-top: 10px;
		font-size: 12px;
		color: #888888;
	}
	.pay_item_name {
		width: 90%;
	}
	.btn_save_order {
		display: flex;
		justify-content: center;
	}
	.btn_save_order .btn_pink {
		width: 100%;
		text-align: center;
	}
	
	
</style>