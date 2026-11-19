import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
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
