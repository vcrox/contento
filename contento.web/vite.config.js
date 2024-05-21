import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0", // This allows connections from any IP address
        port: 5173,
        cors: {
            origin: "*", // Allow all origins (modify as needed)
        },
        hmr: {
            host: "127.0.0.1",
        },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
