import globals from "globals";
import pluginJs from "@eslint/js";
import stylisticJs from '@stylistic/eslint-plugin';

export default [{
	files: [ '**/*.js' ],
	languageOptions: {
		globals: {
			...globals.browser,
			$: 'readonly',
			jQuery: 'readonly'
		},
		sourceType: 'module'
	}
}, pluginJs.configs.recommended, {
	plugins: {
		'@stylistic/js': stylisticJs
	},
	rules: {
    	'@stylistic/js/semi': 'error',
		'no-debugger': 'off',
		'prefer-const': 'error',
		'quote-props': [ 'error', 'as-needed' ]
	}
}];