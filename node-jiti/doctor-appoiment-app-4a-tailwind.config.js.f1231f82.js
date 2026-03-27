"use strict";Object.defineProperty(exports, "__esModule", {value: true}); function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }var _defaultTheme = require('tailwindcss/defaultTheme'); var _defaultTheme2 = _interopRequireDefault(_defaultTheme);
var _forms = require('@tailwindcss/forms'); var _forms2 = _interopRequireDefault(_forms);
var _typography = require('@tailwindcss/typography'); var _typography2 = _interopRequireDefault(_typography);
var _tailwindconfigjs = require('./vendor/wireui/wireui/tailwind.config.js'); var _tailwindconfigjs2 = _interopRequireDefault(_tailwindconfigjs);
var _plugin = require('flowbite/plugin'); var _plugin2 = _interopRequireDefault(_plugin); // 👈 agrega esto

/** @type {import('tailwindcss').Config} */
exports. default = {
    presets: [_tailwindconfigjs2.default],
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
                sans: ['Figtree', ..._defaultTheme2.default.fontFamily.sans],
            },
        },
    },

    plugins: [_forms2.default, _typography2.default, _plugin2.default], // 👈 aquí agregas Flowbite
};
 /* v7-f64cbae907a37254 */