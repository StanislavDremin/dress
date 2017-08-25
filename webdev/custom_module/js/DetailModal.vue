<template>
	<div :class="['modal_detail_wrap', className]">
		<transition name="custom-classes-transition"
				enter-active-class="animated fadeIn"
				leave-active-class="animated fadeOut">
			<div class="modal_overlay" v-if="showModal()" @click.prevent="overlayClick"></div>
		</transition>
		<transition name="custom-classes-transition"
				enter-active-class="animated zoomIn"
				leave-active-class="animated zoomOut">
			<div class="modal_data_wrap" v-show="showModal()">
				<div class="header_modal">
					<slot name="m_head"></slot>
					<div class="modal_close_icon" @click="$emit('onModalClose')"><i class="el-icon-close"></i></div>
				</div>
				<div class="body_modal">
					<slot name="m_body"></slot>
				</div>
				<div class="footer_modal">
					<slot name="m_footer"></slot>
				</div>
			</div>
		</transition>
	</div>
</template>
<script>
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			className: {type: String},
			clickOverlayClose: {type: Boolean}
		},
		data() {
			return {}
		},
		watch: {},
		methods: {
			showModal() {
				if (this.show === true) {
//				    $('body').addClass('overlay_blur');
					let $wrap = $(this.$el).find('.modal_data_wrap');

					$wrap.css({
						'margin-left': '-' + Number($wrap.width() / 2) + 'px',
//						'margin-top': '-' + Number($wrap.height() / 2) + 'px',
					});
				} else {
//			    	$('body').removeClass('overlay_blur');
				}
				return this.show;
			},
			overlayClick() {
				if(this.clickOverlayClose === true){
					this.$emit('onModalClose');
				}
			}
		}
	}
</script>
<style>
	.modal_detail_wrap {
		font-family: 'Roboto Light', sans-serif;
		font-size: 14px;
		color: #2d2d2d;
		line-height: 1.8;
	}
	
	.modal_detail_wrap .modal_overlay {
		background: rgba(0, 0, 0, 0.8);
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		z-index: 500;
	}
	
	.overlay_blur {
		-webkit-filter: blur(5px);
		-moz-filter: blur(5px);
		filter: blur(5px);
		z-index: 0;
		-ms-filter: blur(3px);
		filter: progid:DXImageTransform.Microsoft.Blur(PixelRadius='5');
	}
	
	.modal_data_wrap {
		position: fixed;
		min-width: 700px;
		min-height: 300px;
		background: #fff;
		top: 50%;
		left: 50%;
		z-index: 600;
		border-radius: 5px;
		
	}
	
	.modal_data_wrap .header_modal,
	.modal_data_wrap .body_modal,
	.modal_data_wrap .footer_modal {
		padding: 15px 25px;
	}
	
	.modal_data_wrap .header_modal {
		font-weight: bold;
		font-size: 16px;
	}
	
	.modal_data_wrap .header_modal .modal_close_icon {
		position: absolute;
		right: -30px;
		top: -5px;
		color: #fff;
		font-size: 18px;
		cursor: pointer;
	}
	
	.animated.zoomIn, .animated.zoomOut {
		-webkit-animation-duration: .3s;
		-o-animation-duration: .3s;
		-moz-animation-duration: .3s;
		animation-duration: .3s;
		
		-webkit-animation-delay: .3s;
		-o-animation-delay: .3s;
		-moz-animation-delay: .3s;
		animation-delay: .3s;
	}
</style>