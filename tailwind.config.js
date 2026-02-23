import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "primary-50": "#f8f9fa",
                "primary-100": "#f1f3f5",
                "primary-200": "#e9ecef",
                "primary-300": "#dee2e6",
                "primary-400": "#ced4da",
                "primary-500": "#adb5bd",
                "primary-600": "#868e96",
                "primary-700": "#495057",
                "primary-800": "#343a40",
                "primary-900": "#212529",
            },
        },
    },

    plugins: [forms],
};
