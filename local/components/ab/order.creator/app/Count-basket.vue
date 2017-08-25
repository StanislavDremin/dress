<template>
	<div>
		<div class="count">
            <span @click="update('minus')" class="icon-minus"
		            style="background-image: url(/local/dist/img/icons/icon-basket-minus.png)"></span>
			<span class="count">{{count}}</span>
			<span @click="update('plus')" class="icon-plus"
					style="background-image: url(/local/dist/img/icons/icon-basket-plus.png)"></span>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			product: {
				type: Number|String,
				default: 0
			},
			quantity: {
				type: Number|String
			}
		},
		data() {
			return {
				count: this.quantity || 1
			}
		},
		methods: {
			update(mode){
				if(mode === 'plus'){
					this.count++;
				} else {
					this.count--;
					if(this.count < 1)
						this.count = 1;
				}
				
				this.$emit('update-quantity', {id: this.product, quantity: this.count});
			},
		},
	}
</script>