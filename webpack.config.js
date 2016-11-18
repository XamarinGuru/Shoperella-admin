const autoprefixer = require('autoprefixer');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const path    = require("path");
const webpack = require("webpack");

const BUILD_DIR = path.resolve(__dirname, 'web/dist');
const APP_DIR   = path.resolve(__dirname, 'assets');

const sassLoaders = [
  'css-loader',
  'postcss-loader',
  'sass-loader'
];

const config = {
  entry: APP_DIR + '/index.jsx',
  output: {
    path: BUILD_DIR ,
    publicPath: "/dist/",
    filename: 'bundle.js',
    chunkFilename: "[id].js"
  },
  devtool: 'eval',
  module : {
    loaders: [
      {
        test: /\.js$/,
        include: APP_DIR,
        loader: 'babel',
        query: {
          presets: ["es2015", "react"]
        }
      },
      {
        test: /\.jsx?/,
        include: APP_DIR,
        loader: 'babel',
        query: {
          presets: ["es2015", "react"]
        }
      },
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract('style-loader', 'css-loader!postcss-loader')
      },
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract('style-loader', sassLoaders.join('!'))
      },
      {
        test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'url?limit=10000'
      },
      {
        test: /\.(woff|ttf|eot|svg)(\?[\s\S]+)?$/,
        loader: 'url-loader?limit=10000'
      },
    ]
  },
  plugins: [
    new webpack.ProvidePlugin({
      '$': "jquery",
      'jQuery': "jquery",
      'jquery': "jquery",
      'window.$': 'jquery',
      'window.jQuery': "jquery"
    }),
    new ExtractTextPlugin('main.css'),
    new webpack.DefinePlugin({
      'require.specified': 'require.resolve'
    })
  ],
  postcss: [
    autoprefixer({
      browsers: ['last 2 versions']
    })
  ]
};

module.exports = config;
