<template>
	<div class="property_ration_wrap">
		<div v-if="product">
			<el-table :data="tableStore" height="250" border style="width: 100%" @row-click="setRowSelected">
				<el-table-column label="#" width="50">
					<template scope="scope">{{ scope.$index + 1 }}</template>
				</el-table-column>
				<el-table-column prop="size" label="Размер" width="180">
					<template scope="scope">
						<drop-menu :menuTitle="scope.row.size" menuId="size_menu" :index="scope.$index" :items="sizeItems" @selectMenu="addSize"></drop-menu>
					</template>
				</el-table-column>
				<el-table-column prop="color" label="Цвет">
					<template scope="scope">
						<drop-menu :menuTitle="scope.row.color" :index="scope.$index" menuId="color_menu" :items="colorItems" @selectMenu="addColor"></drop-menu>
					</template>
				</el-table-column>
				<el-table-column>
					<template scope="scope">
						<el-button type="primary" size="small" @click="delRow(scope.$index)">
							<i class="fa fa-trash"></i>
						</el-button>
					</template>
				</el-table-column>
				<tr slot="append">
					<td></td>
					<td colspan="3" style="padding: 18px">
						<el-button type="primary" size="small" @click="addRow">
							<i class="fa fa-plus"></i> Добавить
						
						</el-button>
					</td>
				</tr>
			</el-table>
		</div>
		<div v-else>
			<div class="no_product_data">Прежде чем установить сочетания сохраните изменения.</div>
		</div>
	
	</div>
</template>
<script>
	import { ElTable, ElTableColumn, ElButton } from 'element-ui';
	import 'element-ui/lib/theme-default/index.css';
	import Rest from 'Rest';
	import dropMenu from 'BX/dropMenu.vue';

	Vue.use(Rest, {
		rest: {baseURL: '/rest/admin/ratioProperty'}
	});

	const EmptyData = () => {
		return {
			id: false,
			size: 'Выбрать размер',
			color: 'Выбрать цвет',
		}
	};

	export default{
		props: {
			options: {
				type: Object,
				default: {}
			},
			product: {}
		},
		data(){
			return {
				colorItems: [],
				sizeItems: [],
				tableStore: [EmptyData()]
			}
		},
		computed: {},
		created(){
			let params = {product: this.product};
			params = Object.assign(params, this.options.USER_TYPE_SETTINGS);

			this.$rest.get('/loadData', {params: params}).then(res => {
				if (res.data.STATUS === 1) {
					this.colorItems = res.data.DATA.colors;
					this.sizeItems = res.data.DATA.sizes;
				}
			});
		},
		methods: {
			addSize(data){
				this.tableStore[data.index]['size'] = data.title;
			},
			addColor(data){
				this.tableStore[data.index]['color'] = data.title;
			},
			setRowSelected(row){
//				console.info(row);
			},
			addRow(){
				this.tableStore.push(EmptyData());
			},
			delRow(index){
				this.tableStore.splice(index, 1);
				if (this.tableStore.length === 0) {
					this.tableStore.push(EmptyData())
				}
			}
		},
		components: {
			dropMenu
		},
		mounted(){
		}
	}
</script>
<style>
	.property_ration_wrap {
		background: #fff;
		padding: 25px;
	}
	
	.no_product_data {
		font-size: 100%;
		text-align: center;
	}

</style>