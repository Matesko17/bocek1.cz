const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin'); // For JS minification
const { CleanWebpackPlugin } = require('clean-webpack-plugin'); // Clean build folder

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';

  return {
    entry: {
      main: './assets/js/main.js', // The entry point of your application
      main_sync: './assets/js/main_sync.js', // New entry point
    },
    output: {
      filename: '[name].js', // The name of the output bundle
      path: path.resolve(__dirname, 'build'), // The directory where the bundle will be generated
    },
    plugins: [
      new CleanWebpackPlugin(), // Automatically cleans the build folder before each build
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
      }),
      new MiniCssExtractPlugin({
        filename: 'build.css', // The name of the output CSS file
      }),
    ],
    module: {
      rules: [
        {
          test: /\.css$/i,
          use: [
            MiniCssExtractPlugin.loader, // Extract CSS into separate files
            'css-loader',                // Turns CSS into CommonJS
          ],
        },
        {
          test: /\.scss$/i,
          use: [
            MiniCssExtractPlugin.loader, // Extracts CSS into separate files
            'css-loader',                // Turns CSS into CommonJS
            {
              loader: 'postcss-loader',  // PostCSS loader with autoprefixer
              options: {
                postcssOptions: {
                  plugins: [
                    require('autoprefixer') // Autoprefixer plugin
                  ],
                },
              },
            },
            'sass-loader',                // Compiles Sass to CSS
          ],
        },
        {
          test: /\.(ttf|woff|woff2|eot|otf)$/i, // Match all font formats
          type: 'asset/resource', // Treat fonts as assets
          generator: {
            filename: 'fonts/[name].[hash:4][ext]', // Output name.hash.ext format
          },
        },
        {
          test: /\.svg$/i, // Match all font formats
          type: 'asset/resource', // Treat fonts as assets
          generator: {
            filename: 'svgs/[hash:8][ext]', // Output name.hash.ext format
          },
        },
      ],
    },
    optimization: {
      minimize: isProduction,
      minimizer: [
        isProduction && new TerserPlugin(), // Minifies JS
        isProduction && new CssMinimizerPlugin(), // Minifies CSS
      ].filter(Boolean), // Filter out false values,
    },
    devtool: isProduction ? 'source-map' : 'eval-source-map',
    resolve: {
      extensions: ['.js', '.jsx', '.scss'],
    },
    mode: isProduction ? 'production' : 'development',
  };
};