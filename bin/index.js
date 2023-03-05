#!/usr/bin/env node
const path = require("path");
const fs = require("fs-extra");
const prompts = require("prompts");
const {
  blue,
  cyan,
  green,
  lightGreen,
  lightRed,
  magenta,
  red,
  reset,
  yellow,
} = require("kolorist");
const argv = require("minimist")(process.argv.slice(2));

async function init(projectName, template) {
  const targetDir = projectName || argv._[0] || ".";
  const cwd = process.cwd();
  const root = path.join(cwd, targetDir);
  const renameFiles = {
    _gitignore: ".gitignore",
  };
  console.log(__dirname);
  console.log(`Scaffolding project in ${root}...`);

  await fs.ensureDir(root);
  const existing = await fs.readdir(root);
  if (existing.length) {
    console.error(`Error: target directory is not empty.`);
    process.exit(1);
  }

  const templateName =
    template || argv.t || argv.template || "next" + (argv.ts ? "-ts" : null);

  const templateDir = path.join(
    __dirname,
    `../templates/template-${templateName}`,
  );

  const write = async (file, content) => {
    const targetPath = renameFiles[file]
      ? path.join(root, renameFiles[file])
      : path.join(root, file);
    if (content) {
      await fs.writeFile(targetPath, content);
    } else {
      await fs.copy(path.join(templateDir, file), targetPath);
    }
  };

  const files = await fs.readdir(templateDir);
  for (const file of files.filter((f) => f !== "package.json")) {
    await write(file);
  }

  const pkg = require(path.join(templateDir, `package.json`));
  pkg.name = path.basename(root);
  await write("package.json", JSON.stringify(pkg, null, 2));

  console.log(`\nDone. Now run:\n`);
  if (root !== cwd) {
    console.log(`  cd ${path.relative(cwd, root)}`);
  }
  console.log(`  npm install (or \`yarn\`)  (or \`pnpm\`)`);
  console.log(`  npm run dev (or \`yarn dev\`)  (or \`pnpm dev\`)`);
  console.log();
}

const FRAMEWORKS = [
  {
    name: "next",
    display: "NEXT JS",
    color: yellow,
    variants: [
      {
        name: "vanilla",
        display: "JavaScript",
        color: yellow,
      },
      {
        name: "vanilla-ts",
        display: "TypeScript",
        color: blue,
      },
    ],
  },
  {
    name: "wp-theme-elementor",
    display: "WP THEME ELEMENTOR",
    color: blue,
  },
];

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
        // validate: (value) => (value < 18 ? `Nightclub is 18+ only` : true),
        initial: 0,
        choices: FRAMEWORKS.map((framework) => {
          const frameworkColor = framework.color;
          return {
            title: frameworkColor(framework.display || framework.name),
            value: framework.name,
          };
        }),
      },
    ],
    {
      onCancel: () => {
        throw new Error(red("✖") + " Operation cancelled");
      },
    },
  );

  init(response.projectName, response.framework).catch((err) => {
    console.log(err);
  });
})().catch((e) => {
  console.log(e.message);
});
