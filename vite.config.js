import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import legacy from "@vitejs/plugin-legacy";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
        }),

        legacy({
            targets: ["defaults", "not IE 11"],
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],

    resolve: {
        alias: {
            "~": "node_modules/",
        },
    },

    css: {
        preprocessorOptions: {
            scss: {
                // Suppress deprecation warnings from dependencies (Bootstrap, etc.)
                quietDeps: true,
                silenceDeprecations: ["legacy-js-api"],
            },
        },
    },
});
