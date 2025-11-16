import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import tailwindcss from '@tailwindcss/vite'
import { fileURLToPath, URL } from 'node:url'

export default () => {
  return defineConfig({
    server: {
      host: '0.0.0.0',
      port: 3000,
      strictPort: true
    },
    build: {
      outDir: "./public",
    },
    base: "/app/",
    plugins: [vue(),tailwindcss(),],
      resolve: {
          alias: {
              '@': fileURLToPath(new URL('./src', import.meta.url))
          }
      }
  });
};
