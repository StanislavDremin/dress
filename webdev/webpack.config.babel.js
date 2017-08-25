import path from 'path';
import Encore from '@symfony/webpack-encore';
import BX from './BXConfigComponent';

const Component = new BX({
	baseFolder: path.join('..'),
});

// Component.addComponent({name: 'ab:trello', style: {name: 'app.scss', build: 'style'}});
Component.addComponent({name: 'ab:search.title'});
Component.addComponent({name: 'ab:auth.enter'});
Component.addComponent({name: 'ab:smart.filter'});
// Component.addComponent({
// 	name: 'ab:product.list',
// 	app: ['templates','.default','app', 'app.js'],
// });
Component.addComponent({name: 'ab:catalog.main', style:{name: 'catalog.css', build: 'style'}});
Component.addComponent({name: 'ab:catalog.detail'});
Component.addComponent({name: 'ab:basket.top'});
Component.addComponent({name: 'ab:order.creator'});
Encore
// directory where all compiled assets will be stored
	.setOutputPath('.')

	// what's the public path to this directory (relative to your project's document root dir)
	.setPublicPath(path.resolve('..'))

	// will output as web/build/app.js
	// .addEntry(Component.getBuildPath('ab:trello'), Component.getAppPath('ab:trello'))
	// will output as web/build/global.css
	// .addStyleEntry(Component.getStyleBuild('ab:trello'), Component.getStyleApp('ab:trello'))


	// .addEntry('../local/dist/lib/data_libs', path.resolve('..','local/dist','vendor', 'allLibs.js'))
	// .addEntry('../local/dist/js/components', path.resolve('..','local/dist/vendor', 'components'))
	// .addEntry('../local/dist/js/app', path.resolve('..', 'local/resource/app'))


	/* =================================================== свойство размер + цвет =================================== */
	// .addEntry('../local/modules/dresscode.main/asset/js/ratioProperty', path.resolve('..', 'local/modules/dresscode.main/asset/components/ratioProperty'))
	/* ============================================================================================================== */
	// .addEntry('../local/dist/js/app', path.resolve('..', 'local/resource/app'))
	/* =============================================== поиск в шапке ================================================ */
	// .addEntry(Component.getBuildPath('ab:search.title'), Component.getAppPath('ab:search.title'))
	/* ============================================================================================================== */

	/* ==================================== логин, пароль, восстановление пароля ==================================== */
	// .addEntry(Component.getBuildPath('ab:auth.enter'), Component.getAppPath('ab:auth.enter'))
	/* ============================================================================================================== */
	/* ==================================== фльтр =================================================================== */
	// .addEntry(Component.getBuildPath('ab:smart.filter'), Component.getAppPath('ab:smart.filter'))
	/* ============================================================================================================== */
	/* ==================================== Список товара =========================================================== */
	// .addEntry(Component.getBuildPath('ab:product.list'), Component.getAppPath('ab:product.list'))
	/* ============================================================================================================== */
	/* ==================================== Комплексный каталог ===================================================== */
	.addEntry(Component.getBuildPath('ab:catalog.main'), Component.getAppPath('ab:catalog.main'))
	/* ============================================================================================================== */
	/* ==================================== Карточка товара ========================================================= */
	// .addEntry(Component.getBuildPath('ab:catalog.detail'), Component.getAppPath('ab:catalog.detail'))
	// .addEntry(Component.getBuildPath('ab:basket.top'), Component.getAppPath('ab:basket.top'))
	/* ============================================================================================================== */

	/* ==================================== Корзина и заказ ========================================================= */
	// .addEntry(Component.getBuildPath('ab:order.creator'), Component.getAppPath('ab:order.creator'))
	/* ============================================================================================================== */

	// allow sass/scss files to be processed
	.enableSassLoader()
	.enableVueLoader()

	// .configureBabel(function(babelConfig) {
	// 	babelConfig.presets.push('env')
	// })

	// allow legacy applications to use $/jQuery as a global variable
	// .autoProvidejQuery()

	.enableSourceMaps(false)

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;
const config = Encore.getWebpackConfig();
if(!config.resolve.hasOwnProperty('modules')){
	config.resolve = Object.assign({}, config.resolve, {
		modules: [
			path.join(__dirname, '..', 'local', 'modules', 'ab.tools', 'asset', 'js'),
			'node_modules',
			path.join(__dirname, 'custom_module', 'js'),
		]
	})
}

// console.info(config.entry);
// throw new Error;

// export the final configuration
module.exports = config;