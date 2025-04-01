/**
 * View your website at your own local server.
 * Example: if you're using WP-CLI then the common URL is: http://localhost:8080.
 *
 * http://localhost:5173 is serving Vite on development. Access this URL will show empty page.
 *
 */

import { defineConfig } from "vite";
import { resolve } from "path";
import path from "path";
import { glob } from "glob";
import fs from "fs";
// import { exit } from "process";
// const blocksDir = path.resolve(__dirname, 'templates/blocks');

// Funzione ricorsiva per leggere file nelle sottocartelle
const getFilesInDirectory = (dir, extension) => {
  let results = [];

  // Leggi i contenuti della cartella
  const list = fs.readdirSync(dir);

  list.forEach((file) => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);

    if (stat && stat.isDirectory()) {
      // Se è una cartella, chiamare ricorsivamente la funzione
      results = results.concat(getFilesInDirectory(filePath, extension));
    } else {
      // Se è un file, controlla l'estensione
      if (file.endsWith(extension)) {
        results.push(filePath); // Aggiungi il percorso completo del file
      }
    }
  });

  return results;
};

// Percorso della cartella dei blocchi
const blocksDir = path.resolve(__dirname, "templates/blocks");

// Ottieni i file .js nelle sottocartelle
const jsFiles = getFilesInDirectory(blocksDir, ".js");

// Percorso di base da rimuovere (relativo alla tua cartella `templates/blocks`)
const baseDir = path.resolve(__dirname, "templates/blocks");

// Crea un oggetto di input per Rollup con i percorsi dei file relativi
const inputFiles = jsFiles.reduce((entries, filename) => {
  const relativePath = path.relative(baseDir, filename); // Rimuove il percorso base
  // const name = relativePath.replace(/\.js$/, ''); // Rimuovi l'estensione .js
  const name = path.basename(relativePath, ".js");
  // const name_2 = relativePath.match(/([^/]+)\.js$/);
  // const name_3 = name.split('\\/');
  return { ...entries, [`block/${name}`]: filename }; // Usa il nome del file come chiave per l'input
}, {});

// console.log(inputFiles);

export default ({ mode }) => {
  // const isProduction = () => mode === "production";

  return defineConfig({
    base: "./",

    plugins: [
      {
        handleHotUpdate({ file, server }) {
          if (file.endsWith(".php")) {
            server.ws.send({ type: "full-reload", path: "*" });
          }
        },
      },
      // isProduction() &&
      // compression({
      //   algorithm: "gzip",
      //   exclude: [/\.(html)$/, /\.(svg)$/],
      // }),
      // isProduction() &&
      // compression({
      //   algorithm: "brotliCompress",
      //   exclude: [/\.(br)$ /, /\.(gz)$/],
      //   deleteOriginalAssets: false,
      // }),
    ],

    css: {
      preprocessorOptions: {
        scss: {
          // Inietta `global.scss` in ogni file SCSS
          additionalData: `
          @import "./assets/src/scss/abstracts/_variables.scss";
          @import "bootstrap/scss/functions";
          @import "bootstrap/scss/variables";
          @import "bootstrap/scss/variables-dark";
          @import "bootstrap/scss/maps";
          @import "bootstrap/scss/mixins";
          @import "bootstrap/scss/utilities";
          `,
        },
      },
      devSourcemap: false,
    },

    filenameHashing: false,

    build: {
      chunkSizeWarningLimit: 1000,

      // enable source maps
      sourcemap: false,

      // disable clear dist
      emptyOutDir: false,

      // emit manifest so PHP can find the hashed files
      manifest: true,

      outDir: resolve(__dirname, "assets/dist/"),

      // don't base64 images
      assetsInlineLimit: 0,

      rollupOptions: {
        input: {
          "js/main": resolve(__dirname + "/assets/src/js/main.js"),
          main: resolve(__dirname + "/assets/src/scss/main.scss"),
          ...(() =>
            glob
              .sync(resolve(__dirname, "assets/src/scss/[!_]*.scss"))
              .reduce((entries, filename) => {
                const [, name] = filename.match(/([^/]+)\.scss$/);
                return { ...entries, [name]: filename };
              }, {}))(),

          // Inserisci i file dei blocchi come input
          ...inputFiles,

          // ...(() =>
          //   glob
          //     .sync(resolve(__dirname, "templates/blocks/*/*.js"))
          //     .reduce((entries, filename) => {
          //       const [, name] = filename.match(/([^/]+)\.js$/);
          //       return { ...entries, [`block/${name}`]: filename };
          //     }, {}))(),
        },
        output: {
          entryFileNames: (assetInfo) => {
            let dirType = assetInfo.name.split("/");

            if (dirType[0] === "block") {
              return "js/[name].js";
            } else {
              return "[name].js";
            }
          },
          // entryFileNames: "[name].js",
          manualChunks: {
            // Crea un chunk separato per locomotive-scroll
            "locomotive-scroll": ["locomotive-scroll"],
            // Crea un chunk separato per swiper
            swiper: ["swiper/bundle"],
          },
          chunkFileNames: (chunkInfo) => {
            const chunkName = chunkInfo.name;
            // Posiziona locomotive-scroll e swiper nella cartella modules
            if (chunkName === "locomotive-scroll" || chunkName === "swiper") {
              return "js/modules/[name].js";
            }
            return "[name].js"; // Per gli altri chunk
          },
          assetFileNames: (assetInfo) => {
            const name = assetInfo.names;
            const original = assetInfo.originalFileNames;

            if (!name || !original) {
              // console.warn("Asset undefined:", assetInfo); // Log per debug
              return "[ext]/[name].[ext]"; // Default fallback
            }

            let extType = name[Object.keys(name)[0]].split(".");
            const filePath = original[Object.keys(original)[0]]; // Percorso completo del file

            // group fonts in a folder
            if (
              extType[1] === "woff" ||
              extType[1] === "woff2" ||
              extType[1] === "ttf" ||
              extType[1] === "eot" ||
              (extType[1] === "svg" && filePath.includes("fonts"))
            ) {
              return "fonts/[name].[ext]";
            }

            // Gruppo di immagini
            if (
              ["svg", "webp", "avif", "gif", "jpg", "jpeg", "png"].includes(
                extType[1]
              )
            ) {
              return "img/[name].[ext]";
            }

            // if (extType[1] === "css" && filePath.includes("blocks")) {
            // console.log(extType, filePath);
            // return "[ext]/block/[name].[ext]";
            // }

            return "[ext]/[name].[ext]";
          },
        },
      },
    },

    server: {
      // required to load scripts from custom host
      cors: {
        origin: "*",
      },

      // We need a strict port to match on PHP side.
      strictPort: true,
      port: 5173,
    },

    resolve: {
      alias: {
        "@": "/static",
      },
    },
  });
};
