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
// import sassGlobImports from "vite-plugin-sass-glob-import";
import { compression } from "vite-plugin-compression2";
// import biomePlugin from 'vite-plugin-biome';

// Get the relative path of the vite.config.js file for the alias
// const fullPath = import.meta.url.slice(0, import.meta.url.lastIndexOf('/'));
// const getWpContentIndex = fullPath.indexOf('wp-content');
// const wpContentPath = fullPath.slice(getWpContentIndex);

export default ({ mode }) => {
  const isProduction = () => mode === "production";

  return defineConfig({
    base: "./",

    plugins: [
      // sassGlobImports(),
      isProduction() &&
        compression({
          algorithm: "gzip",
          exclude: [/\.(html)$/, /\.(svg)$/],
        }),
      isProduction() &&
        compression({
          algorithm: "brotliCompress",
          exclude: [/\.(br)$ /, /\.(gz)$/],
          deleteOriginalAssets: false,
        }),
    ],

    css: {
      preprocessorOptions: {
        scss: {
          // Inietta `global.scss` in ogni file SCSS
          additionalData: `
          @import "/assets/src/scss/abstracts/_variables.scss";
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
        // external: ["locomotive-scroll"],
        input: {
          "js/main": resolve(__dirname + "/assets/src/js/main.js"),
          // main: resolve(__dirname + "/assets/src/scss/main.scss"),
          ...(() =>
            glob
              .sync(resolve(__dirname, "assets/src/scss/[!_]*.scss"))
              .reduce((entries, filename) => {
                const [, name] = filename.match(/([^/]+)\.scss$/);
                return { ...entries, [name]: filename };
              }, {}))(),

          ...(() =>
            glob
              .sync(resolve(__dirname, "templates/blocks/*/*.js"))
              .reduce((entries, filename) => {
                const [, name] = filename.match(/([^/]+)\.js$/);
                return { ...entries, [`block/${name}`]: filename };
              }, {}))(),
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
      // cors: {
      //   origin: "*",
      // },

      // We need a strict port to match on PHP side.
      strictPort: true,
      port: 5173,
    },

    resolve: {
      alias: {
        "@": path.resolve(__dirname, "static"),
      },
    },
  });
};
