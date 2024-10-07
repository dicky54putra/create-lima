import { execSync } from "child_process";

const createDatabase = async (databaseName: string) => {
  try {
    execSync(`mysql -u root -e "CREATE DATABASE \`${databaseName}\`"`, {
      cwd: process.cwd(),
    });
    console.log("%s Database created!", "DONE");
    return Promise.resolve();
  } catch (err: any) {
    const isDatabaseExist =
      err &&
      err.message &&
      typeof err.message === "string" &&
      err.message.includes("database exists");

    if (isDatabaseExist) {
      console.log(
        "%s Error, database is already exist, please provide different name!",
        "ERROR"
      );

      //   const questionsPrompt = await inquirer.prompt([
      //     {
      //       type: "input",
      //       name: "database_name",
      //       message: "What's the database name?",
      //     },
      //   ]);
      //   await createDatabase(questionsPrompt.database_name);
    } else {
      console.log("%s Error while creating database", "ERROR");
      Promise.reject();
    }
  }
};

export default createDatabase;
