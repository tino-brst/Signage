module.exports = {
	extends: [
		// add more generic rulesets here, such as:
		// 'eslint:recommended',
		'plugin:vue/strongly-recommended'
	],
	rules: {
		// override/add rules settings here, such as:
		// 'vue/no-unused-vars': 'error'
		"indent": ["error", "tab"],
		"vue/html-indent": ["error", "tab"]
	}
}