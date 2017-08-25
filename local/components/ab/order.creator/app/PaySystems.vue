<template>
	<product-modal :show="showWindow" @onModalClose="showPays = false" class-name="pay_system_wrap">
		<div class="basket_pays_wrap" slot="m_body">
			<pre>{{payItems}}</pre>
		</div>
	</product-modal>
</template>
<script>
	import DetailModal from 'DetailModal.vue';
	
	export default {
		props: ['payItems'],
		data() {
			return {
				showPays: false
			}
		},
		methods: {},
		watch: {},
		created() {
		},
		beforeUpdate() {
		},
		components: {
			DetailModal
		}
	}
</script>
<style>

</style>