import axios from "axios";
import { promises, readFile, writeFile } from "fs-extra";
import path from "path";

const getSecretKey = async () => {
  const response = await axios({
    url: "https://api.wordpress.org/secret-key/1.1/salt/",
    responseEncoding: "utf-8",
  });

  return response.data;
};

type TReplaceWpConfig = {
  databaseName: string;
  databaseUsername: string;
  databasePassword: string;
};
const replaceWpConfig = async ({
  databaseName,
  databaseUsername,
  databasePassword,
}: TReplaceWpConfig) => {
  const filePath = path.join(process.cwd(), `${databaseName}/wp-config.php`);
  const newSecretKey = await getSecretKey();

  readFile(filePath, "utf-8", function (err, data) {
    if (err) throw err;

    let newData = data;

    newData = newData.replace(/database_name_here/gim, databaseName);
    newData = newData.replace(/username_here/gim, databaseUsername);
    newData = newData.replace(/password_here/gim, databasePassword);

    /**
     * This will match everything that start with `define('AUTH_KEY'` until it
     * has a newline and followed by a collection of strange symbol.
     * this symbol: /**#@-*(end with slash)
     */
    const regexDefineBlock = new RegExp(
      /define\( \'AUTH_KEY\'.*(?=(\n\/\*\*\#\@\-\*\/))/s
    );

    newData = newData.replace(regexDefineBlock, newSecretKey);

    writeFile(filePath, newData, "utf-8", function (err) {
      if (err) throw err;
      console.log("%s WP Config updated successfully!", "DONE");
    });
  });
};

const createWpConfig = async ({
  databaseName,
  databaseUsername,
  databasePassword,
}: TReplaceWpConfig) => {
  await promises.rename(
    path.join(process.cwd(), `${databaseName}/wp-config-sample.php`),
    path.join(process.cwd(), `${databaseName}/wp-config.php`)
  );

  await replaceWpConfig({ databaseName, databasePassword, databaseUsername });
};

export default createWpConfig;
