<template>
	<div class="filters_data" v-if="items">
		<filter-item v-for="(dataFilter, id) in filterItems"
				:dataFilter="dataFilter"
				:key="id"
				@openedFilterData="opened" :showValues="id === isOpened">
		</filter-item>
	</div>
</template>
<script>
	import filterItem from './filterItem.vue';
	
	export default{
		data(){
			return {
				isOpened: false,
				filterItems: this.items
			}
		},
		computed: {},
		props: {
			items: {type: Object, default: {}}
		},
		components: {
			filterItem
		},
		methods: {
			opened(id){
				this.isOpened = id;
			}
		},
		created(){
		}
	}
</script>
<style>
	.filters_data {
		padding: 20px 0;
		display: flex;
	}
</style>