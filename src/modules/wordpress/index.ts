import { exec } from "child_process";
import downloadWordpress from "./1.download-wordpress";
import extract from "./2.extract";
import setupTheme from "./3.setup-theme";
import createDatabase from "./4.create-database";
import createWpConfig from "./5.create-wp-config";

const execute = async () => {
  const projectName = "dev-wp";
  const repo = "https://github.com/made-indonesia/starter-theme-custom.git";
  const mysql = {
    username: "root",
    password: "",
  };
  const placeholder = {
    projectName: projectName,
    THEME_DOMAIN: projectName.toUpperCase(),
  };

  try {
    // await downloadWordpress();
    // await extract(projectName);
    // await setupTheme({
    //   dirTarget: projectName,
    //   repo: repo,
    //   placeholder: placeholder,
    // });
    // await createDatabase(projectName);
    await createWpConfig({
      databaseName: projectName,
      databaseUsername: mysql.username,
      databasePassword: mysql.password,
    });
  } catch (error) {
    console.log(error);
  }
};

try {
  execute();
} catch (error) {
  console.log(error);
}
