<template>
	<div class="filter_item">
		<el-button class="filter_item_btn_top" :type="btnType" icon="arrow-down" @click="showPopup(dataFilter.id)">
			{{filterTitle}}
		</el-button>
		<i class="fa fa-times close_filter_item" v-if="this.btnType !== 'default'" @click="clearItem"></i>
		<div class="popup_item" v-show="showValues" @mouseenter="hoveBlock = true" @mouseleave="hoveBlock = false">
			<el-checkbox-group v-model="checkModel">
				<vue-perfect-scrollbar class="scroll-area" v-once :settings="settingsScroll">
					<div class="filter-head__desc__value">
						<div class="check_item" v-for="valueItem in dataFilter.values" :key="valueItem.id">
							<el-checkbox :label="valueItem.value" @change="checkItem(valueItem)" ></el-checkbox>
						</div>
					</div>
				</vue-perfect-scrollbar>
				<div class="filter-head__desc__btn">
					<a @click="setFilter" class="btn_pink" href="javascript:">Применить</a>
				</div>
			</el-checkbox-group>
		</div>
	</div>
</template>
<script>
	import { ElButton, ElCheckbox } from 'element-ui';
	import Util from 'Utils';
	import VuePerfectScrollbar from 'vue-perfect-scrollbar';
	import EventBus from 'EventBus';
	import queryString from 'query-string';
	
	Vue.use(VuePerfectScrollbar);
	Vue.use(Util);
	export default {
		data: () => ({
			checkedFilter: {},
			filterTitle: '',
			declTitle: {},
			declNum: null,
			btnType: 'default',
			checkModel: [],
			settingsScroll: {
				maxScrollbarLength: 160
			},
			hoveBlock: false,
			urlQuery: queryString.parse(window.location.search)
		}),
		props: {
			dataFilter: {},
			showValues: {type: Boolean, default: false}
		},
		watch: {
			checkedFilter(value){
				let cnt = this.$count(value);
				if (cnt === 1) {
					this.filterTitle = this.$shift(value).value;
				} else if (cnt > 1) {
					this.filterTitle = cnt + ' ' + this.declNum(cnt);
				} else {
					this.filterTitle = this.dataFilter.name;
					this.checkModel = [];
				}

				this.btnType = cnt > 0 ? 'danger' : 'default';
			}
		},
		methods: {
			showPopup(id){
				if (!this.showValues) {
					this.$emit('openedFilterData', id);
				} else {
					this.$emit('openedFilterData', null);
				}
			},

			checkItem(data){
				let checks = {...this.checkedFilter};

				if (checks.hasOwnProperty(data.id)) {
					delete checks[data.id];
				} else {
					checks[data.id] = data;
				}

				this.checkedFilter = checks;
			},
			clearItem(){
				this.checkedFilter = {};
				this.$emit('openedFilterData', null);
				EventBus.getBus().$emit('setFilter',{code: this.dataFilter.code, selected: this.checkedFilter});
			},
			setFilter(){
				this.$emit('openedFilterData', null);
				EventBus.getBus().$emit('setFilter',{code: this.dataFilter.code, selected: this.checkedFilter});
			},
			isChecked(valueItem){
				
				return false;
			}
		},
		created(){
			this.filterTitle = this.dataFilter.name;
			this.declNum = this.$declOfNum([this.dataFilter.name, this.dataFilter.name + 'a', this.dataFilter.name + 'ов']);
			
			if(this.urlQuery[this.dataFilter.code] !== undefined){
				let splitValues = this.urlQuery[this.dataFilter.code].split(',');
				this.$foreach(this.dataFilter.values, (el) => {
					let k = splitValues.indexOf(el.id);
					if(k !== -1 && this.dataFilter.values[k] !== undefined){
						this.checkItem(el);
						this.checkModel.push(el.value);
					}
				});
			}
			
		},
		components: {
			VuePerfectScrollbar
		},
		mounted(){
		
		}
	}
</script>
<style>
	.filter_item {
		margin: 0 10px;
	}
	.filter_item .filter_item_btn_top {
		border: none;
		font-family: 'Roboto Light',sans-serif;
		font-size: 12px;
		padding-left: 0;
	}
	.filter_item .el-icon-arrow-down {
		float: right;
		margin-left: 7px;
		font-size: 8px;
		padding-top: 3px;
	}
	.popup_item {
		display: flex;
		flex-direction: column;
		position: absolute;
		width: 222px;
		height: 266px;
		background-color: #fff;
		box-shadow: 0 0 10px 0 rgba(0, 0, 0, .15);
		z-index: 500;
		line-height: 2.2;
		padding: 10px 15px;
		overflow: hidden;
	}
	.filter-head__desc__value {
		height: 180px;
		display: flex;
		flex-direction: column;
	}
	.close_filter_item {
		display: inline-block;
		margin-left: 5px;
		font-size: 18px;
		position: relative;
		color: #fff;
		left: -31px;
		margin-right: -31px;
	}
	
	.filter_item .el-button {
		padding-right: 31px !important;
	}
	.filter-head__desc__btn {
		margin-top: 30px;
	}
</style>