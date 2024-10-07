import { exec } from "child_process";
import { unlink } from "fs-extra";
import path from "path";

type TSetupTheme = {
  dirTarget: string;
  repo: string;
  placeholder: Record<string, string>;
};
const setupTheme = async ({ dirTarget, repo }: TSetupTheme) => {
  const themeName = dirTarget.replace("dev-", "");
  const themeDir = `${dirTarget}/wp-content/themes/${themeName}`;

  await Promise.resolve(exec(`git clone ${repo} ${themeDir}`));

  await unlink(path.join(`${themeDir}/.git`));
};

export default setupTheme;
