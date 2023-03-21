#!/usr/bin/env node
const { red } = require("kolorist");
const prompts = require("prompts");
const FRAMEWORKS = require("./framework.data");
const init = require("./init");
const argv = require("minimist")(process.argv.slice(2));

const checkVariant = (prev) => {
  return FRAMEWORKS.filter((item) => item.name === prev);
};

(async () => {
  const argTargetDir = argv._[0];

  const response = await prompts(
    [
      {
        type: argTargetDir ? null : "text",
        name: "projectName",
        message: "Project name:",
        initial: "lima-project",
      },
      {
        type: "select",
        name: "framework",
        message: "Select a framework:",
        initial: 0,
        choices: FRAMEWORKS.map((framework) => {
          const frameworkColor = framework.color;
          return {
            title: frameworkColor(framework.display || framework.name),
            value: framework.name,
          };
        }),
      },
      {
        type: (framework) =>
          checkVariant(framework)[0].variants ? "select" : null,
        name: "variant",
        message: (framework) =>
          "Select a variant:" + checkVariant(framework)[0].variants,
        initial: 0,
        choices: (framework) =>
          checkVariant(framework)
            ? checkVariant(framework)[0].variants?.map((variant) => {
                const variantColor = variant.color;
                return {
                  title: variantColor(variant.display || variant.name),
                  value: variant.name,
                };
              })
            : [],
      },
    ],
    {
      onCancel: () => {
        throw new Error(red("✖") + " Operation cancelled");
      },
    },
  );

  init(response.projectName, response.variant || response.framework).catch(
    (err) => {
      console.log(err);
    },
  );
})().catch((e) => {
  console.log(e.message);
});
