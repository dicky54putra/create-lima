// eslint-disable-next-line no-undef
module.exports = {
  extends: ["eslint:recommended"],
  env: {
    browser: true,
  },
  parserOptions: {
    ecmaVersion: "latest",
    sourceType: "module",
  },
  rules: {
    "no-console": ["warn", { allow: ["warn", "error", "log"] }],
    "no-unused-vars": ["warn"],
  },
};
