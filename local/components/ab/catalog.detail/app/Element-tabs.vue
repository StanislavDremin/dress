<template>
	<div class="container">
		<div class="elements-tabs" v-loading="this.isLoading">
			<el-tabs type="card" v-model="activeTabName">
				
				<el-tab-pane name="reviews" label="Отзывы">
					<div class="element-tab-hero">
						<div class="element-tab-hero__btn">
							<a class="element-tab-btn" href="javascript:" @click.prevent="modals.review = true">Оставить отзыв</a>
						</div>
						<div class="element-tab-hero__filters">
							<span>Сортировать по:</span>
							<a class="isActive" href="#">дате</a>
							<a href="#">оценке</a>
							<a href="#">полезность</a>
						</div>
					</div>
					<div class="element-tab-comment" v-for="item in reviewList">
						<div class="element-tab-comment__items-left">
							<div class="name">
								<span>{{item.USER_SHORT_NAME}}</span> ({{ item.DATE }})
							</div>
							<div class="hero">
                                <span class="stars">
                                    <el-rate v-model="item.RATING" disabled></el-rate>
                                </span>
								<span class="colors"><span>Цвет:</span> {{item.COLOR}}</span>
								<span class="size"><span>Размер:</span> {{item.SIZE}}</span>
							</div>
							<div class="comment">
								{{item.PREVIEW_TEXT}}
							</div>
							<div class="desc">
                                    <span><b>Соответствие размеру</b> <br>{{item.ACORD_SIZE}}
                                    </span>
								<span><b>Соответствие фото</b> <br>{{item.ACORD_PHOTO}}
                                    </span>
								<span><b>Соответствие описанию</b> <br>{{item.ACORD_DESC}}</span>
							</div>
						</div>
						
						<div class="element-tab-comment__items-right">
							<span class="title">Отзыв полезен?</span>
							<div class="count">
                                <span class="icon-down" @click="item.AVAIL > 0 ? item.AVAIL-- : false"
		                                style="background-image: url(/local/dist/img/icons/icon-count-el-1.png)"></span>
								
								<span class="counter">{{item.AVAIL}}</span>
								
								<span class=" icon-up" @click="item.AVAIL++"
										style="background-image: url(/local/dist/img/icons/icon-count-el-2.png)"></span>
							</div>
						</div>
					</div>
				</el-tab-pane>
				
				<el-tab-pane name="comments" label="Комментарии">
					<div class="element-tab-hero">
						<div class="element-tab-hero__btn">
							<a class="element-tab-btn" href="javascript:" @click.prevent="modals.comment = true">Оставить комментарий</a>
						</div>
					</div>
					
					<div class="element-tab-comment" v-for="item in commentList">
						<div class="element-tab-comment__items-left">
							<div class="name">
								<span>{{item.USER_SHORT_NAME}}</span> ({{ item.DATE }})
							</div>
							<div class="comment">
								{{item.PREVIEW_TEXT}}
							</div>
						</div>
					</div>
				</el-tab-pane>
				
				<el-tab-pane name="questions" label="Вопросы">
					<div class="element-tab-hero">
						<div class="element-tab-hero__btn">
							<a class="element-tab-btn" href="javascript:" @click.prevent="modals.question = true">Задать вопрос</a>
						</div>
					</div>
					<div class="element-tab-comment" v-for="item in questionList">
						<div class="element-tab-comment__items-left">
							<div class="name">
								<span>{{item.USER_SHORT_NAME}}</span> ({{ item.DATE }})
							</div>
							<div class="comment">
								{{item.PREVIEW_TEXT}}
							</div>
						</div>
					</div>
				</el-tab-pane>
			
			</el-tabs>
		</div>
		<modal-detail :show="modals.review" @onModalClose="modals.review = false">
			<div slot="m_head">Оставить отзыв</div>
			<div slot="m_body">
				<div class="body_comment_form" v-loading="isLoadingSave">
					<form method="post">
						<div class="form_groups">
							<label>Мой отзыв</label>
							<el-input type="textarea" name="text" v-validate="'required'" :rows="5" v-model="commentForm.model.text"></el-input>
							<error-wrap :show="errors.has('text')">{{ errors.first('text') }}</error-wrap>
						</div>
						<div class="form_groups">
							<div class="horizontal_items">
								<div class="horizon_item">
									<label>Соответствие размеру</label>
									<el-select v-model="commentForm.model.accordSize" placeholder="Выбрать">
										<el-option
												v-for="item in commentForm.AccordsForm"
												:key="item.value"
												:label="item.label"
												:value="item.label">
										</el-option>
									</el-select>
								</div>
								<div class="horizon_item">
									<label>Соответствие фото</label>
									<el-select v-model="commentForm.model.accordPhoto" placeholder="Выбрать">
										<el-option
												v-for="item in commentForm.AccordsForm"
												:key="item.value"
												:label="item.label"
												:value="item.label">
										</el-option>
									</el-select>
								</div>
								<div class="horizon_item">
									<label>Соответствие описанию</label>
									<el-select v-model="commentForm.model.accordDesc" placeholder="Выбрать">
										<el-option
												v-for="item in commentForm.AccordsForm"
												:key="item.value"
												:label="item.label"
												:value="item.label">
										</el-option>
									</el-select>
								</div>
							</div>
							<div class="horizontal_items">
								<div class="horizon_item" v-if="commentForm.storage.colors.length > 0">
									<label>Цвет</label>
									<el-select v-model="commentForm.model.color" placeholder="Выбрать">
										<el-option
												v-for="item in commentForm.storage.colors"
												:key="item.ID"
												:label="item.UF_NAME"
												:value="item.UF_NAME">
										</el-option>
									</el-select>
									<!--<error-wrap :show="errors.has('color')">{{ errors.first('color') }}</error-wrap>-->
								</div>
								<div class="horizon_item">
									<label>Размер</label>
									<el-select v-model="commentForm.model.size" placeholder="Выбрать">
										<el-option
												v-for="item in commentForm.storage.sizes"
												:key="item.ID"
												:label="item.UF_NAME"
												:value="item.UF_NAME">
										</el-option>
									</el-select>
									<error-wrap :show="errors.has('size')">{{ errors.first('size') }}</error-wrap>
								</div>
								<div class="horizon_item rating_wrap">
									<label>Ваша оценка</label>
									<div class="stars_wrap">
										<el-rate v-model="commentForm.model.rating"></el-rate>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div slot="m_footer">
				<div class="modal_btn">
					<a href="javascript:" @click.prevent="saveReview" class="btn_pink">Отправить</a>
				</div>
			</div>
		</modal-detail>
		
		<modal-detail :show="modals.comment" @onModalClose="modals.comment = false">
			<div slot="m_head">Оставить комментарий</div>
			<div slot="m_body" v-loading="isLoadingSave">
				<div class="body_comment_form">
					<form method="post">
						<div class="form_groups">
							<label>Мой комментарий</label>
							<el-input type="textarea" name="text_comment" v-validate="'required'" :rows="5" v-model="commentsFormText.model.text"></el-input>
							<error-wrap :show="errors.has('text_comment')">{{ errors.first('text_comment') }}</error-wrap>
						</div>
					</form>
				</div>
			</div>
			<div slot="m_footer">
				<div class="modal_btn">
					<a href="javascript:" class="btn_pink" @click.prevent="saveComment">Отправить</a>
				</div>
			</div>
		</modal-detail>
		
		<modal-detail :show="modals.question" @onModalClose="modals.question = false">
			<div slot="m_head">Задать вопрос</div>
			<div slot="m_body" v-loading="isLoadingSave">
				<div class="body_comment_form">
					<form method="post">
						<div class="form_groups">
							<label>Мой вопрос</label>
							<el-input type="textarea" name="text_question" v-validate="'required'" :rows="5" v-model="questionForm.model.text"></el-input>
							<error-wrap :show="errors.has('text_question')">{{ errors.first('text_question') }}</error-wrap>
						</div>
					</form>
				</div>
			</div>
			<div slot="m_footer">
				<div class="modal_btn">
					<a href="javascript:" class="btn_pink" @click.prevent="saveQuestion">Отправить</a>
				</div>
			</div>
		</modal-detail>
	</div>
</template>

<script>
	import modalDetail from 'DetailModal.vue';
	import Ajax from 'preloader/RestService';
	import ErrorWrap from 'validator/ErrorWrap.vue';

	const Rest = new Ajax({baseURL: '/rest/elementComments'});

	const dictionary = {
		ru: {
			custom: {
				text: {required: () => 'Заполните поле'},
				color: {required: () => 'Укажите цвет'},
				size: {required: () => 'Укажите размер'},
				text_comment: {required: () => 'Напишите отзыв'},
				text_question: {required: () => 'Задайте вопрос'},
			},
		},
	};

	export default {
		props: {
			sizes: {type: Array},
			colors: {type: Array},
			product: {type: Number, default: 0}
		},
		data() {
			return {
				activeName: 'first',
				value1: null,
				modals: {
					review: false,
					comment: false,
					question: false
				},
				commentForm: {
					storage: {
						sizes: [],
						colors: []
					},
					model: {
						text: '',
						size: '',
						accordSize: '',
						accordPhoto: '',
						accordDesc: '',
						color: '',
						rating: 0,
					},

					AccordsForm: [
						{label: 'Полное', value: 'yes'},
						{label: 'Частично', value: 'so'},
						{label: 'Вообще не то', value: 'no'},
					]
				},
				element: null,
				reviewList: [],
				commentList: [],
				questionList: [],
				commentsFormText: {
					model: {
						text: ''
					}
				},
				questionForm: {
					model: {
						text: ''
					}
				},
				activeTabName: 'reviews',
				isLoading: false,
				isLoadingSave: false,
			};
		},
		methods: {
			getParamsProduct() {
				Rest.get('/getParameters', {params: {id: this.product}}).then(res => {
					if (res.data.STATUS === 1) {
						this.commentForm.storage.sizes = res.data.DATA.sizes;
						this.commentForm.storage.colors = res.data.DATA.colors;
					}
				});
			},

			resetFormFields(commentForm) {
				let model = this[commentForm]['model'];
				this.$foreach(model, (el, code) => {
					if (typeof model[code] === 'string') {
						model[code] = '';
					} else {
						model[code] = 0;
					}
				});
				
				this.errors.clear();
				this.modals.review = false;
				this.modals.comment = false;
				this.modals.question = false;
			},
			
			/* ========================== Получение комментов ======================================================= */
			getReviewList(){
				this.isLoading = true;
				Rest.get('/getReviews', {params: {element: this.element}}).then(res => {
					if(res.data.STATUS === 1){
						this.reviewList = res.data.DATA;
					}
					this.isLoading = false;
				});
			},
			getCommentList(){
				this.isLoading = true;
				Rest.get('/getComments', {params: {element: this.element}}).then(res => {
					if(res.data.STATUS === 1){
						this.commentList = res.data.DATA;
					}
					this.isLoading = false;
				});
			},
			getQuestionList(){
				this.isLoading = true;
				Rest.get('/getQuestions', {params: {element: this.element}}).then(res => {
					if(res.data.STATUS === 1){
						this.questionList = res.data.DATA;
					}
					this.isLoading = false;
				});
			},
			/* ====================================================================================================== */
			
			
			/* ==================================== сохранение комментов и вопросов ================================= */
			saveReview() {
				let validateFields = {
					text: this.commentForm.model.text,
					size: this.commentForm.model.size,
//					color: this.commentForm.model.color,
				};
				this.$validator.validateAll(validateFields).then(result => {
					if (result) {
						this.isLoadingSave = true;
						Rest.post('/saveReview', Object.assign({}, this.commentForm.model, {element: this.element})).then(res => {
							if (res.data.STATUS === 1) {
								swal('', 'Отзыв сохранен', 'success');
								this.resetFormFields('commentForm');
								this.isLoadingSave = false;
								this.getReviewList();
							}
						});
					}
				});
			},
			
			saveComment(){
				let validateFields = {
					text_comment: this.commentsFormText.model.text,
				};
				this.$validator.validateAll(validateFields).then(result => {
					if (result) {
						this.isLoadingSave = true;
						Rest.post('/saveComment', Object.assign({}, this.commentsFormText.model, {element: this.element})).then(res => {
							if (res.data.STATUS === 1) {
								swal('', 'Комментарий сохранен', 'success');
								this.resetFormFields('commentsFormText');
								this.isLoadingSave = false;
								this.getCommentList();
							}
						});
					}
				});
			},
			
			saveQuestion(){
				let validateFields = {
					text_question: this.questionForm.model.text,
				};
				this.$validator.validateAll(validateFields).then(result => {
					if (result) {
						this.isLoadingSave = true;
						Rest.post('/saveQuestion', Object.assign({}, this.questionForm.model, {element: this.element})).then(res => {
							if (res.data.STATUS === 1) {
								swal('', 'Вопрос сохранен', 'success');
								this.resetFormFields('questionForm');
								this.isLoadingSave = false;
								this.getQuestionList();
							}
						});
					}
				});
			},
			/* ====================================================================================================== */
		},
		components: {
			modalDetail,
			ErrorWrap
		},
		created() {
			this.$validator.updateDictionary(dictionary);
			this.getParamsProduct();
			this.$validator.attach('size', 'required');
			this.$validator.attach('color', 'required');
			this.element = this.product;
			this.getReviewList();
		},
		mounted(){
		},
		watch:{
			activeTabName(val){
				switch (val){
					case 'comments':
						this.getCommentList();
						break;
					case 'questions':
						this.getQuestionList();
						break;
					case 'reviews':
						this.getReviewList();
						break;
				}
			}
		}
	};
</script>
<style>
	.form_groups {
		display: flex;
		flex-direction: column;
		font-size: 90%;
	}
	
	.form_groups label {
		font-weight: bold;
	}
	
	.horizontal_items {
		display: flex;
		margin-top: 15px;
		justify-content: space-between;
	}
	
	.horizon_item {
		display: flex;
		flex-direction: column;
	}
	
	.el-select .el-input .el-input__inner {
		color: #0f0f11;
		border-color: #b7b7b7;
		height: 40px;
		border-radius: 0;
	}
	
	.horizon_item {
		color: #0f0f11 !important;
	}
	
	.horizon_item .el-input__icon.el-icon-caret-top:before {
		content: "\E603";
	}
	
	.rating_wrap {
		width: 193px;
	}
	
	.rating_wrap .stars_wrap {
		margin-top: 13px;
	}
	
	.modal_btn {
		display: flex;
		justify-content: center;
	}
	
	.modal_btn .btn_pink {
		width: 193px;
		text-align: center;
	}
	
	.body_comment_form .error_field_wrap {
		margin-top: 0;
	}
</style>
