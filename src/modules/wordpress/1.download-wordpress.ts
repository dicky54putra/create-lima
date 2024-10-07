import axios from "axios";
import fs from "fs-extra";
import cliProgress from "cli-progress";

const downloadWordpress = async () => {
  const url = "https://wordpress.org/latest.zip";
  const filePath = "./wordpress.zip";

  const writer = fs.createWriteStream(filePath);
  const progress = new cliProgress.SingleBar(
    {
      format: `Downloading || [{bar}] || {percentage}% || {value}/{total} Chunks`,
    },
    cliProgress.Presets.legacy
  );

  const response = await axios({
    url,
    method: "GET",
    responseType: "stream",
  });

  const totalLength = parseInt(response.headers["content-length"], 10);
  progress.start(totalLength, 1);
  let downloadedLength = 0;

  response.data.pipe(writer);
  const stream = response.data;

  stream.on("data", (data: any) => {
    downloadedLength += data.length;
    progress.update(Math.round(downloadedLength));
  });

  return new Promise((resolve, reject) => {
    writer.on("finish", () => {
      progress.stop(); // Stop the progress bar when done
      resolve("Download complete");
    });
    writer.on("error", (err) => {
      progress.stop(); // Stop the progress bar if an error occurs
      reject(err);
    });
  });
};

export default downloadWordpress;
