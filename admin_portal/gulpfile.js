const { src, dest, watch, parallel } = require("gulp");

// CSS
const sass = require("gulp-sass")(require("sass"));
const autoprefixer = require("autoprefixer");
const cssnano = require("cssnano");
const postcss = require("gulp-postcss");
const sourcemaps = require("gulp-sourcemaps");

// Javascript & Webpack
const webpack = require("webpack-stream");
const terser = require("gulp-terser-js");
const rename = require("gulp-rename");

const paths = {
	scss: "src/scss/**/*.scss",
	js: "src/js/**/*.js",
};

function css() {
	return (
		src(paths.scss)
			.pipe(sourcemaps.init())
			.pipe(sass({ outputStyle: "expanded" }))
			.pipe(postcss([autoprefixer()]))
			.pipe(sourcemaps.write("."))
			.pipe(dest("public/css"))
	);
}

function javascript() {
	return (
		src(paths.js)
			.pipe(
				webpack({
					module: {
						rules: [
							{
								test: /\.css$/i,
								use: ["style-loader", "css-loader"],
							},
						],
					},
					mode: "production",
					watch: false,
					entry: "./src/js/app.js",
				})
			)
			.pipe(sourcemaps.init())
			.pipe(terser())
			.pipe(sourcemaps.write(".", { sourceMappingURL: (file) => `${file.basename}.min.map` }))
			.pipe(rename({ suffix: ".min" }))
			.pipe(dest("public/js"))
	);
}

function dev(done) {
	watch(paths.scss, css);
	watch(paths.js, javascript);
	done();
}

exports.css = css;
exports.js = javascript;
exports.dev = parallel(css, javascript, dev);