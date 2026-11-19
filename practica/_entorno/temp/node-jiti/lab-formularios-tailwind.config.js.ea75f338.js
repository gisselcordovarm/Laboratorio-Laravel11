"use strict";Object.defineProperty(exports, "__esModule", {value: true}); function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }var _defaultTheme = require('tailwindcss/defaultTheme'); var _defaultTheme2 = _interopRequireDefault(_defaultTheme);

/** @type {import('tailwindcss').Config} */
exports. default = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                paper: '#f7f3ec',
                card: '#fffdf8',
                ink: '#1b1a17',
                muted: '#6f685c',
                line: '#e5ddcd',
                forest: {
                    DEFAULT: '#22503f',
                    deep: '#173a2d',
                },
                gold: '#c2a468',
                brick: '#a2402f',
            },
            fontFamily: {
                display: ['Fraunces', 'Georgia', 'serif'],
                sans: ['Inter', ..._defaultTheme2.default.fontFamily.sans],
                mono: ['"JetBrains Mono"', ..._defaultTheme2.default.fontFamily.mono],
            },
            boxShadow: {
                card: '0 30px 70px -45px rgba(27, 26, 23, 0.45)',
                lift: '0 18px 40px -30px rgba(27, 26, 23, 0.5)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
        },
    },
    plugins: [],
};
 /* v7-a54e77c82d5e5322 */