var webpack = require('webpack');
var path = require('path');
var inProduction = (process.env.NODE_ENV === 'production');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
	entry: {
		admin: ['./src/js/admin.js'],
		vendor: ['vue', 'axios']
		// screen: [...]
	},
	output: {
		path: path.resolve(__dirname, './dist'),
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
	resolve: {
		alias: {
	    	'vue$': 'vue/dist/vue.esm.js'
	  	}
	},
	plugins: [
		// junto todas las librerias en uso en un archivo aparte
		// (en app.js queda solo el codigo de la applicacion)
		new webpack.optimize.CommonsChunkPlugin({
			name: 'vendor'
		}),
		// extraccion de estilos a archivo aparte
		new ExtractTextPlugin("[name].css")
	]
};

if (inProduction) {
	module.exports.plugins.push(
		new webpack.optimize.UglifyJsPlugin()
	);
	// algunos plugins usan LoaderOptionsPlugin para determinar su salida (css minificado o no, etc)
	module.exports.plugins.push(
		new webpack.LoaderOptionsPlugin({
			minimize: true
		})
	);
}
