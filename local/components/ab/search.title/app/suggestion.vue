<template>
	<div style="display: inline-block" @mouseleave="isMouseOver = false" @mouseenter="isMouseOver = true">
		<input type="text" placeholder="Найти товар" @input="searching" name="q"
				@keyup.prevent.down="onDown"
				@keyup.prevent.up="onUp"
				@keyup.prevent.enter="onEnter"
				@click="showSuggestion = inputValue.length > 2"
				v-model="inputValue"/>
		<div :class="{'search_suggestion animated': true, 'fadeIn': showSuggestion}" v-show="showSuggestion">
			<div id="floatingBarsG" v-if="loader">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<ul v-if="items" v-show="items.products || items.sections">
				<li class="cats_title" v-if="products">Товары</li>
				
				<li v-for="(product, index) in products"
						:class="{'suggest_item': true, 'item_active': product.IS_ACTIVE}"
						:key="product.ID"
						@mouseenter="onHoverItem(index)">
					<div class="search_item_img"><a :href="product.DETAIL_PAGE_URL"><img v-if="product.IMG" :src="product.IMG.src"/></a></div>
					<div class="title_search_product"><a :href="product.DETAIL_PAGE_URL">{{product.NAME}}</a></div>
					<div class="price_item">{{product.PRICE_FORMAT}} <i class="fa fa-rouble"></i></div>
				</li>
				
				<!-- ========================================================== -->
				<li class="cats_title" v-if="sections">Категории</li>
				
				<li v-for="(section, index) in sections"
						:class="{'suggest_item categories': true, 'item_active': section.IS_ACTIVE}"
						:key="section.ID" @mouseenter="hoverSection(index)">
					<div class="title_search_product"><a :href="section.SECTION_PAGE_URL">{{section.NAME}}</a></div>
				</li>
				
				<li class="suggest_item categories">
					<div class="title_search_product">
						<a class="all_result_link" :href="linkAllResult">Все результаты</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			items: {type: Object, default: {products: [], sections: []}},
			loader: {type: Boolean, default: false},
			startSearch: {type: Boolean, default: false},
			searching: {type: Function},
			SearchPath: {type: String, default: '/search/'}
		},
		data() {
			return {
				index: -1,
				listItems: [],
				inputValue: '',
				sections: false,
				products: false,
				isMouseOver: false,
				showSuggestion: false
			}
		},
		computed:{
			linkAllResult(){
				return this.$addUrlParam(this.SearchPath, {q: encodeURIComponent(this.inputValue)});
			}
		},
		watch: {
			startSearch(value){
				this.showSuggestion = value;
			},
			items(){
				this.sections = this.items.sections;
				this.products = this.items.products;
				
				this.listItems = this.$arrayMerge(this.items.products, this.items.sections);
			},
			inputValue(value){
				this.showSuggestion = value.length > 2;
			},
			index(){
				if(this.index < this.listItems.length){
					let products = [];
					this.$foreach(this.products, (el, i) => {
						el.IS_ACTIVE = el.NAME === this.listItems[this.index].NAME;
						products.push(el);
					});
					this.products = products;

					let sections = [];
					this.$foreach(this.sections, (el, i) => {
						el.IS_ACTIVE = el.NAME === this.listItems[this.index].NAME;
						sections.push(el);
					});
					this.sections = sections;
				}
			}
		},
		methods: {
			onDown(){
				if (this.index <= -1) { // если это вообще первое нажатие при поиске
					this.index = 0; // берем первый элемент массива
				} else {
					this.index++; // или прибавим, ежели стрелку уже тыркали
				}
				if (this.index >= this.listItems.length) { // если дошли до конца списка, то закинемся на первый пункт
					this.index = 0;
				}
			},
			onUp(){
				this.index--; // сразу выставляем пердыдущий элемент массива
				if (this.index <= -1) {
					// если после поиска, сразу нажали вверх, то перекинемся на последний эл. списка
					this.index = this.listItems.length - 1;
				}
			},
			onEnter(index){
				if(index instanceof KeyboardEvent) {
					index = this.index;
				}
				if(this.listItems.hasOwnProperty(index)){
					if(this.listItems[index].hasOwnProperty('DETAIL_PAGE_URL')){
						window.location.assign(this.listItems[index]['DETAIL_PAGE_URL']);
					} else if(this.listItems[index].hasOwnProperty('SECTION_PAGE_URL')){
						window.location.assign(this.listItems[index]['SECTION_PAGE_URL']);
					} else {
						this.inputValue = this.listItems[index]['NAME'];
					}
				}
			},
			onHoverItem(index){
				this.index = index;
			},
			hoverSection(index){
				this.index = this.products.length + index;
			},
		},
		mounted(){
			$(window.document).on('keyup', (ev) => {
				if(ev.keyCode === 27 && this.isMouseOver === false){
					this.showSuggestion = false;
				}
			});
			$(window.document).on('click', (ev) => {
				if(this.isMouseOver === false){
					this.showSuggestion = false;
				}
			});
		}
	}
</script>
<style>
	.search_suggestion {
		position: absolute;
		background: #fff;
		padding: 15px 0;
		font-size: 12px !important;
		min-width: 320px;
		box-shadow: 0 16px 20px rgba(101, 101, 101, 0.65);
		min-height: 150px;
		z-index: 500;
	}
	.search_suggestion li {
		padding: 0 15px;
	}
	.search_suggestion .suggest_item {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.search_suggestion .suggest_item a {
		background: transparent;
		font-size: 12px;
	}
	.search_suggestion .item_active {
		background-color: #d8e1e8;
		cursor: pointer;
	}
	.search_suggestion .search_item_img {
		min-width: 80px;
		text-align: center;
	}
	.search_suggestion .cats_title {
		font-weight: bold;
		padding: 10px 15px 5px;
		text-align: left;
		border-bottom: 1px solid #d8e1e8;
		margin-bottom: 10px;
	}
	.search_suggestion .categories {
		padding: 7px 15px;
	}
	
	.search_suggestion #floatingBarsG{
		position:relative;
		width:17px;
		height:21px;
		margin:auto;
	}
	.search_suggestion .price_item {
		min-width: 75px;
	}
	.search_suggestion .title_search_product {
		text-align: left;
	}
	.search_suggestion .all_result_link {
		color: #09f;
		text-decoration: underline;
	}
	.search_suggestion .blockG{
		position:absolute;
		background-color:rgb(255,255,255);
		width:3px;
		height:7px;
		border-radius:2px 2px 0 0;
		-o-border-radius:2px 2px 0 0;
		-ms-border-radius:2px 2px 0 0;
		-webkit-border-radius:2px 2px 0 0;
		-moz-border-radius:2px 2px 0 0;
		transform:scale(0.4);
		-o-transform:scale(0.4);
		-ms-transform:scale(0.4);
		-webkit-transform:scale(0.4);
		-moz-transform:scale(0.4);
		animation-name:fadeG;
		-o-animation-name:fadeG;
		-ms-animation-name:fadeG;
		-webkit-animation-name:fadeG;
		-moz-animation-name:fadeG;
		animation-duration:0.832s;
		-o-animation-duration:0.832s;
		-ms-animation-duration:0.832s;
		-webkit-animation-duration:0.832s;
		-moz-animation-duration:0.832s;
		animation-iteration-count:infinite;
		-o-animation-iteration-count:infinite;
		-ms-animation-iteration-count:infinite;
		-webkit-animation-iteration-count:infinite;
		-moz-animation-iteration-count:infinite;
		animation-direction:normal;
		-o-animation-direction:normal;
		-ms-animation-direction:normal;
		-webkit-animation-direction:normal;
		-moz-animation-direction:normal;
	}
	
	.search_suggestion #rotateG_01{
		left:0;
		top:8px;
		animation-delay:0.3095s;
		-o-animation-delay:0.3095s;
		-ms-animation-delay:0.3095s;
		-webkit-animation-delay:0.3095s;
		-moz-animation-delay:0.3095s;
		transform:rotate(-90deg);
		-o-transform:rotate(-90deg);
		-ms-transform:rotate(-90deg);
		-webkit-transform:rotate(-90deg);
		-moz-transform:rotate(-90deg);
	}
	
	.search_suggestion #rotateG_02{
		left:2px;
		top:3px;
		animation-delay:0.416s;
		-o-animation-delay:0.416s;
		-ms-animation-delay:0.416s;
		-webkit-animation-delay:0.416s;
		-moz-animation-delay:0.416s;
		transform:rotate(-45deg);
		-o-transform:rotate(-45deg);
		-ms-transform:rotate(-45deg);
		-webkit-transform:rotate(-45deg);
		-moz-transform:rotate(-45deg);
	}
	
	.search_suggestion #rotateG_03{
		left:7px;
		top:1px;
		animation-delay:0.5225s;
		-o-animation-delay:0.5225s;
		-ms-animation-delay:0.5225s;
		-webkit-animation-delay:0.5225s;
		-moz-animation-delay:0.5225s;
		transform:rotate(0deg);
		-o-transform:rotate(0deg);
		-ms-transform:rotate(0deg);
		-webkit-transform:rotate(0deg);
		-moz-transform:rotate(0deg);
	}
	
	.search_suggestion #rotateG_04{
		right:2px;
		top:3px;
		animation-delay:0.619s;
		-o-animation-delay:0.619s;
		-ms-animation-delay:0.619s;
		-webkit-animation-delay:0.619s;
		-moz-animation-delay:0.619s;
		transform:rotate(45deg);
		-o-transform:rotate(45deg);
		-ms-transform:rotate(45deg);
		-webkit-transform:rotate(45deg);
		-moz-transform:rotate(45deg);
	}
	
	.search_suggestion #rotateG_05{
		right:0;
		top:8px;
		animation-delay:0.7255s;
		-o-animation-delay:0.7255s;
		-ms-animation-delay:0.7255s;
		-webkit-animation-delay:0.7255s;
		-moz-animation-delay:0.7255s;
		transform:rotate(90deg);
		-o-transform:rotate(90deg);
		-ms-transform:rotate(90deg);
		-webkit-transform:rotate(90deg);
		-moz-transform:rotate(90deg);
	}
	
	.search_suggestion #rotateG_06{
		right:2px;
		bottom:2px;
		animation-delay:0.832s;
		-o-animation-delay:0.832s;
		-ms-animation-delay:0.832s;
		-webkit-animation-delay:0.832s;
		-moz-animation-delay:0.832s;
		transform:rotate(135deg);
		-o-transform:rotate(135deg);
		-ms-transform:rotate(135deg);
		-webkit-transform:rotate(135deg);
		-moz-transform:rotate(135deg);
	}
	
	.search_suggestion #rotateG_07{
		bottom:0;
		left:7px;
		animation-delay:0.9385s;
		-o-animation-delay:0.9385s;
		-ms-animation-delay:0.9385s;
		-webkit-animation-delay:0.9385s;
		-moz-animation-delay:0.9385s;
		transform:rotate(180deg);
		-o-transform:rotate(180deg);
		-ms-transform:rotate(180deg);
		-webkit-transform:rotate(180deg);
		-moz-transform:rotate(180deg);
	}
	
	.search_suggestion #rotateG_08{
		left:2px;
		bottom:2px;
		animation-delay:1.035s;
		-o-animation-delay:1.035s;
		-ms-animation-delay:1.035s;
		-webkit-animation-delay:1.035s;
		-moz-animation-delay:1.035s;
		transform:rotate(-135deg);
		-o-transform:rotate(-135deg);
		-ms-transform:rotate(-135deg);
		-webkit-transform:rotate(-135deg);
		-moz-transform:rotate(-135deg);
	}
	
	
	
	@keyframes fadeG{
		0%{
			background-color:rgb(0,0,0);
		}
		
		100%{
			background-color:rgb(255,255,255);
		}
	}
	
	@-o-keyframes fadeG{
		0%{
			background-color:rgb(0,0,0);
		}
		
		100%{
			background-color:rgb(255,255,255);
		}
	}
	
	@-ms-keyframes fadeG{
		0%{
			background-color:rgb(0,0,0);
		}
		
		100%{
			background-color:rgb(255,255,255);
		}
	}
	
	@-webkit-keyframes fadeG{
		0%{
			background-color:rgb(0,0,0);
		}
		
		100%{
			background-color:rgb(255,255,255);
		}
	}
	
	@-moz-keyframes fadeG{
		0%{
			background-color:rgb(0,0,0);
		}
		
		100%{
			background-color:rgb(255,255,255);
		}
	}
</style>