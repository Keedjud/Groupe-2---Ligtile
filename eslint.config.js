import js from "@eslint/js";
import globals from "globals";
import pluginVue from "eslint-plugin-vue";
import { defineConfig } from "eslint/config";

export default defineConfig([
    {
        ignores: [
            "vendor/**",
            "public/build/**",
            "node_modules/**",
            "bootstrap/ssr/**",
        ],
    },
    {
        files: ["**/*.{js,mjs,cjs,vue}"],
        plugins: { js },
        extends: ["js/recommended"],
        languageOptions: {
            globals: { ...globals.browser, ...globals.node },
        },
    },
    ...pluginVue.configs["flat/essential"],
    {
        rules: {
            // Les vues sont nommées par leur fonction (Login, Quiz…) — intentionnel
            "vue/multi-word-component-names": "off",
            // Fichiers intentionnellement vides pendant le développement
            "vue/valid-template-root": "off",
        },
    },
]);
