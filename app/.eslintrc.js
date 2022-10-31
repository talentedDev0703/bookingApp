module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint'
  },
  extends: [
    // https://github.com/vuejs/eslint-plugin-vue#priority-a-essential-error-prevention
    // consider switching to `plugin:vue/strongly-recommended` or `plugin:vue/recommended` for stricter rules.
    'plugin:vue/strongly-recommended'
  ],
  // required to lint *.vue files
  plugins: [
    'vue'
  ],
  // add your custom rules here
  rules: {
    'vue/html-indent': ["error", 4, {
        "attribute": 1,
        "closeBracket": 0,
        "ignores": []
    }],
    "vue/script-indent": ["error", 4, {
        "baseIndent": 1,
        "switchCase": 0,
        "ignores": []
    }]
  }
}
