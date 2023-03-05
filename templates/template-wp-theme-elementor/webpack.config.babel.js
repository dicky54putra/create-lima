import path from "path";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import webpack from "webpack";
import autoprefixer from "autoprefixer";
import sass from "sass";
import { CleanWebpackPlugin } from "clean-webpack-plugin";
import CssMinimizerPlugin from "css-minimizer-webpack-plugin";
import CleanTerminalPlugin from "clean-terminal-webpack-plugin";
import BrowserSyncPlugin from "browser-sync-webpack-plugin";
import ESLintWebpackPlugin from "eslint-webpack-plugin";
import config from "./compiler.options";
const { ProgressPlugin } = webpack;

const entries = {};
const entriesRaw = [...config.js, ...config.sass].map((entry) => {
  if (typeof entry === "string") {
    const name = path.parse(path.basename(entry)).name;
    return {
      name,
      src: entry,
    };
  }
  return entry;
});
entriesRaw.forEach((item) => {
  const cssExt = /\.(css|sass|scss)$/;
  const jsExt = /\.(js)$/;
  const isCss = cssExt.test(item.src);
  const isJs = jsExt.test(item.src);
  const prefix = isCss ? "css/" : isJs ? "js/" : false;
  if (prefix) entries[`${prefix}${item.name}`] = item.src;
});

// eslint-disable-next-line no-undef
module.exports = {
  mode: "development",
  entry: entries,
  stats: "minimal",
  devtool: "source-map",
  watchOptions: {
    ignored: /node_modules/,
  },
  output: {
    // eslint-disable-next-line no-undef
    path: path.resolve(__dirname, "./assets/dist"),
    globalObject: "this",
    filename: (pathData) => {
      return pathData.chunk.name.indexOf("css/") !== -1
        ? "[name].__unused__.js"
        : "[name].min.js";
    },
  },
  resolve: {
    fallback: {
      fs: false,
    },
  },
  resolveLoader: {
    modules: ["node_modules"],
    extensions: [".js", ".json"],
    mainFields: ["loader", "main"],
  },
  performance: {
    hints: false,
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            // eslint-disable-next-line no-undef
            configFile: path.resolve(__dirname, ".babelrc"),
          },
        },
      },
      {
        test: /\.s[ac]ss$/i,
        exclude: /node_modules/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              sourceMap: true,
              url: false,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: [autoprefixer],
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true,
              implementation: sass,
              sassOptions: {
                outputStyle: "compressed",
              },
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new CleanTerminalPlugin({
      beforeCompile: true,
      onlyInWatchMode: false,
    }),
    new MiniCssExtractPlugin({
      filename: "[name].min.css",
    }),
    new ESLintWebpackPlugin({
      extensions: ["js", "mjs", "jsx", "ts", "tsx"],
      formatter: "visualstudio",
    }),
    new CleanWebpackPlugin({
      protectWebpackAssets: false,
      cleanOnceBeforeBuildPatterns: ["css/dist/**", "js/dist/**"],
      cleanAfterEveryBuildPatterns: ["**/*.__unused__.*"],
    }),
    new BrowserSyncPlugin(config.browserSync),
    new ProgressPlugin(),
  ],
  optimization: {
    splitChunks: {
      cacheGroups: {
        vendors: {
          test: /[\\/]node_modules[\\/]/,
          name: "js/vendors",
          chunks: "all",
        },
      },
    },
    minimizer: [new CssMinimizerPlugin()],
  },
};
