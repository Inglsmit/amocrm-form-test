module.exports = {
	src: {
		amo: 'src/amo/**',
		pug: [ 'src/pug/**/*.pug', '!' + 'src/pug/**/_*.pug' ],
		scss: 'src/scss/**/*.scss',
		js: 'src/js/**/*.js',
		img: 'src/img/**'
	},
	dist: {
		amo: 'dist/amo',
		pug: 'dist',
		scss: 'dist/css',
		js: 'dist/js',
		img: 'dist/img'
	}
};
