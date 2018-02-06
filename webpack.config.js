var webpack = require('webpack');
var path = require('path');
var inProduction = (process.env.NODE_ENV === 'production');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
	entry: {
		admin: ['./src/js/admin.js'],
		vendor: ['vue', 'axios', 'vuex']
		// screen: [...]
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
		// limpia carpeta de salida (dist) ante cada recompilacion
		// (evito acumular compilaciones viejas)
		new CleanWebpackPlugin(['dist']),
		// junto todas las librerias en uso en un archivo aparte
		// (en app.js queda solo el codigo de la applicacion)
		new webpack.optimize.CommonsChunkPlugin({
			name: 'vendor'
		}),
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
		// se va a tener que configurar para redireccionar llamados a la api, etc
		// (o probar con pasar la base_url desde Codeigniter)
		// proxy: {
		// 	"*": "http://localhost:8000/",
		// }
		noInfo: true,
		overlay: true
	},
	// ante un error en el codigo final empaquetado "error en linea 3.532 bundle.js" 
	// (que incluye varias librerias, etc), anda a saber con que linea del codigo original 
	// se corresponde. Source maps mapean el codigo original al empaquetado para apuntar 
	// los errores a su origen ("error en linea 142 archivoTal.js").
	devtool: '#eval-source-map'
};

if (inProduction) {
	module.exports.devtool = '#source-map';
	
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
