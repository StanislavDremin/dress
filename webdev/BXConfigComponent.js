/**
 * Created by GrandMaster on 26.06.17.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

import path from 'path';

export default class {
	constructor(props){
		props = props || {};

		this.defaultProps = {
			componentFolder: 'local'
		};

		this.options = Object.assign({}, this.defaultProps, props);
		this.componants = [];
		this.pathApp = [];
		this.pathBuild = [];

		this.entry = {};
		this.components = {};
	}


	addComponent(params = {}){
		if(!params.hasOwnProperty('name')){
			throw new Error('Name parameters is empty');
		}

		this.components[params.name] = {
			app: {path: '', name: ''},
			build: {path: '', name: ''},
		};

		let name = params.name.trim().split(':'),
			templateComponent = '';

		let app = params.hasOwnProperty('app') ? params.app : ['app','app.js'],
			templateFileName = params.hasOwnProperty('buildName') ? params.buildName : 'script';

		templateComponent = params['template'] !== undefined ? params['template'] : '.default';

		let appFileName = app.pop();
		appFileName.slice(1, app.length - 1);

		this.pathApp = path.resolve(
			this.options.baseFolder,
			this.options.componentFolder,
			'components',
			name[0],
			name[1],
			app.join(path.sep),
		);

		this.pathBuild = path.join(
			this.options.baseFolder,
			this.options.componentFolder,
			'components',
			name[0],
			name[1],
			'templates',
			templateComponent,
			// templateFileName
		);

		this.entry = {
			[this.pathBuild]: this.pathApp
		};

		this.components[params.name]['app'] = {
			path: this.pathApp,
			name: appFileName
		};
		this.components[params.name]['build'] = {
			path: this.pathBuild,
			name: templateFileName
		};

		if(params.hasOwnProperty('style')){
			let styleApp = params.style.hasOwnProperty('name') ? params.style.name : 'app.scss',
				styleBuild = params.style.hasOwnProperty('build') ? params.style.build : 'style';

			this.components[params.name]['app']['css'] = path.join(this.pathApp, styleApp);
			this.components[params.name]['build']['css'] = path.join(this.pathBuild, styleBuild);
		}

		return this;
	}

	getOption(name){
		return this.options[name] !== undefined ? this.options[name] : null;
	}

	setOption(name, value){
		this.options[name] = value;
	}

	getEntry(name){
		return this.components[name];
	}

	getBuildPath(name){
		let build = this.components[name]['build'];
		return path.join(build.path, build.name);
	}

	getAppPath(name){
		let app = this.components[name]['app'];
		return path.join(app.path, app.name);
	}

	getStyleApp(name){
		return this.components[name]['app']['css'];
	}

	getStyleBuild(name){
		return this.components[name]['build']['css'];
	}
};