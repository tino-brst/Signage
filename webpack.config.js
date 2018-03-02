var webpack = require('webpack');
var path = require('path');
var inProduction = (process.env.NODE_ENV === 'production');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
	entry: {
		admin: [
			'./src/admin/js/main.js',
			// ... css para main.js
		],
		screen: [
			'./src/screen/js/main.js',
			// ... css para main.js
		]
	},
	output: {
		path: path.resolve(__dirname, './dist'),
		publicPath: '/dist/',
		filename: '[name].js'
	},
	module: {
		rules: [
			{
				// loader para uso de Vue Single-file-components (.vue)
				test: /\.vue$/,
				loader: 'vue-loader',
				options: {
					loaders: {
						// extraigo el estilo de los componentes a un archivo aparte
						// (para referencia desde las vistas)
						css: ExtractTextPlugin.extract({
							use: 'css-loader'
			          	})
					}
				}
			},
			{
				enforce: 'pre',
				test: /\.(js|vue)$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			},
			{ 
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			}
		]
	},
	plugins: [
		// definicion de constantes globales (Webpack las reemplaza durante la compilacion)
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"development"'
			},
			'API_URL': '"index.php/api/"'
		}),
		// limpia carpeta de salida (dist) ante cada recompilacion
		// (evito acumular compilaciones viejas)
		new CleanWebpackPlugin(['dist']),
		// extraccion de estilos a archivo aparte
		new ExtractTextPlugin("[name].css")
	],
	resolve: {
		alias: {
	    	'vue$': 'vue/dist/vue.esm.js'
	  	},
	  	// para poder obviar extensiones a la hora de importar (import ... from ...)
	  	extensions: ['*', '.js', '.vue', '.json']
	},
	devServer: {
		// mando solicitudes (incluyendo las que van a la api "/index.php/api/") al servidor php
		// (y no al webpack-dev-server que solo tiene los archivos compilados)
		port: 8089,
		proxy: {
			"*": "http://localhost:8000",
		},
		noInfo: true,
		overlay: true
	},
	// ante un error en el codigo final empaquetado "error en linea 3.532 admin.js" 
	// (que incluye varias librerias, etc), anda a saber con que linea del codigo original 
	// se corresponde. Source maps mapean el codigo original al empaquetado y apuntan 
	// los errores a su origen ("error en linea 142 archivoTal.js").
	devtool: '#eval-source-map'
};


// ajustes a realizar al compilar para produccion
if (inProduction) {
	module.exports.devtool = '#source-map';
	// definicion de constantes globales
	module.exports.plugins.push(
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"production"'
			}
		})
	);
	// minimiza archivos javascript
	module.exports.plugins.push(
		new webpack.optimize.UglifyJsPlugin({
			sourceMap: true,
			compress: {
				warnings: false
			}
		})
	);
	// algunos plugins usan LoaderOptionsPlugin para determinar su salida (css minificado o no, etc)
	module.exports.plugins.push(
		new webpack.LoaderOptionsPlugin({
			minimize: true
		})
	);
}
