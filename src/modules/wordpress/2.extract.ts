import path from "path";
import primitiveExtractZip from "extract-zip";
import cliProgress from "cli-progress";
import { ensureDir, move, promises, unlink } from "fs-extra";

const extract = async (dirTarget: string) => {
  try {
    const file = path.join(process.cwd(), "wordpress.zip");

    const progress = new cliProgress.SingleBar(
      {
        format: `Extracting || [{bar}] || {percentage}% || {value}/{total} Chunks`,
      },
      cliProgress.Presets.legacy
    );

    progress.start(100, 0);
    await primitiveExtractZip(file, {
      dir: path.join(process.cwd()),
      onEntry() {
        progress.update(30);
      },
    });
    // await unlink(file);
    progress.update(60);

    await ensureDir(path.dirname(path.join(process.cwd(), "wordpress")));
    progress.update(80);

    await promises.rename(
      path.join(process.cwd(), "wordpress"),
      path.join(process.cwd(), dirTarget)
    );

    progress.update(100);

    progress.stop();
    return Promise.resolve();
  } catch (err) {
    console.log("%s WordPress extraction failed!", "ERROR");
    Promise.reject(err);
  }
};

export default extract;
