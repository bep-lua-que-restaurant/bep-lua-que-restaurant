import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });

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
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
