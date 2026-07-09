import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  // 🔥 On force la base à la racine pour que /@vite/client soit accessible directement
  base: '/', 
  root: './',

  css: {
    devSourcemap: false,
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler', // Force l'utilisation du compilateur natif stable
        loadPaths: [resolve(__dirname, 'node_modules')],
      },
    },
  },

  server: {
    host: '0.0.0.0',
    port: 3000,
    strictPort: true,
    watch: {
      usePolling: true,
      interval: 1000,
      ignored: ['**/node_modules/**', '**/vendor/**', '**/package-lock.json'],
    },
    hmr: {
      host: 'localhost',
      port: 3000,
    },
  },
  build: {
    outDir: 'public/dist',
    manifest: true,
    rollupOptions: {
      input: resolve(__dirname, 'assets/js/app.js'),
    },
  },
});