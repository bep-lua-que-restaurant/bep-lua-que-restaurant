import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        watch: {
            usePolling: true,
        },
        host: "localhost",
        port: 5173, // Cổng Vite đang chạy
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/public.js",
                "resources/js/thungan.js",
                "resources/js/datban.js",
                "resources/js/danhsach.js",
            ],
            refresh: true,
        }),
    ],
});
