<template>
	<span class="link_item_wrap">
		<a id="menuId" href="javascript:" :class="classLink" @click.prevent="show">
			<slot>
				<span>{{menuTitle}}</span>
			</slot>
		</a>
	</span>
</template>
<script>
	"use strict";
	export default {
		props: {
			items: {
				type: Array,
				default: [],
			},
			menuId: {
				type: String,
				default: BX.util.getRandomString()
			},
			menuTitle: {type: String, default: 'Выбрать'},
			className: {},
			index: {}
		},
		computed: {
			classLink(){
				let c = 'ajax_link';
				if(this.className){
					c += ' '+ this.className
				}
				
				return c;
			}
		},
		methods: {
			compileMenu() {
				let menu = [];
				menu = this.items.map(el => {
					el.index = this.index;
					return {
						text: el.title,
						onclick: this.clickMenu.bind(this, el),
					};
				});

				return menu;
			},
			show(e){
				BX.PopupMenu.show(this.menuId, e.currentTarget, this.compileMenu(), {
					angle: 'top',
					offsetLeft: 30
				});
			},
			close(){
				BX.PopupMenu.close(this.menuId);
			},
			destroy(){
				BX.PopupMenu.destroy(this.menuId);
			},
			clickMenu(item) {
				this.$emit('selectMenu', item);
				this.destroy();
			}
		}
	}
</script>
<style>
	.ajax_link {
		color: #2675d7;
		text-decoration: none !important;
	}
	
	.ajax_link:hover {
		color: #61a9e5;
		text-decoration: none !important;
	}
	
	.ajax_link span {
		border-bottom: 1px dashed #2675d7;
	}
	
	.ajax_link:hover span {
		border-bottom: 1px dashed #61a9e5;
		transition: 0.3s;
	}
	.menu-popup-item {
		min-width: 100px;
	}
</style>