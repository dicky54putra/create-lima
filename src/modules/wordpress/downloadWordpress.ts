import axios from "axios";
import { exec } from "child_process";
import fs from "fs-extra";

const downloadWordpress = async () => {
  const url = "https://wordpress.org/latest.zip";
  const filePath = "./wordpress.zip";

  const writer = fs.createWriteStream(filePath);

  const response = await axios({
    url,
    method: "GET",
    responseType: "stream",
  });

  response.data.pipe(writer);

  return new Promise((resolve, reject) => {
    writer.on("finish", resolve);
    writer.on("error", reject);
  });
};

exec(
  "git clone https://github.com/made-indonesia/starter-hello-elementor-child.git"
);

export default downloadWordpress;
