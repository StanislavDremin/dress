<template>
	<span v-show="show" :class="['error_field_wrap animated', {[effect]: true}]">
		<span class="error_field">
			<slot></slot>
			<i class="fa fa-caret-down"></i>
		</span>
	</span>
</template>
<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			effect: {
				type: String,
				default: 'slideInUp'
			},
		}
	}
</script>
<style>
	.error_field_wrap {
		background: #ff5356;
		color: #fff;
		padding: 3px 7px;
		border-radius: 3px;
		position: absolute;
		font-size: 90%;
		margin-top: -53px;
	}
	
	.error_field_wrap .fa-caret-down {
		position: absolute;
		color: #ff5356;
		bottom: -12px;
		left: 3%;
		font-size: 20px;
	}
</style>