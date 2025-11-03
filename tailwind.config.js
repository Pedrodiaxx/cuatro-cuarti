import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import wireuiConfig from './vendor/wireui/wireui/tailwind.config.js';
import flowbite from 'flowbite/plugin'; // 👈 agrega esto

/** @type {import('tailwindcss').Config} */
export default {
    presets: [wireuiConfig],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        './vendor/wireui/wireui/src/**/*.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/WireUi/**/*.php',
        './vendor/wireui/wireui/src/Components/**/*.php',

        './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js', // 👈 muy importante
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography, flowbite], // 👈 aquí agregas Flowbite
};
