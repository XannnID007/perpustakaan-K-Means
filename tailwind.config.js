import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Color scheme coklat muda/cream
                primary: {
                    50: '#fdf8f6',
                    100: '#f2e8e5',
                    200: '#eaddd7',
                    300: '#e0cfc7',
                    400: '#d2bab0',
                    500: '#bfa094',
                    600: '#a18072',
                    700: '#7f6152',
                    800: '#6b4d3a',
                    900: '#553f2d',
                },
                cream: {
                    50: '#fefcfb',
                    100: '#fef7f0',
                    200: '#fdeee0',
                    300: '#fce0c8',
                    400: '#fac99b',
                    500: '#f8b572',
                    600: '#f29e4c',
                    700: '#e8833a',
                    800: '#d16d2f',
                    900: '#b5581f',
                },
                brown: {
                    50: '#fdf8f6',
                    100: '#f2e8e5',
                    200: '#eaddd7',
                    300: '#e0cfc7',
                    400: '#d2bab0',
                    500: '#bfa094',
                    600: '#a18072',
                    700: '#7f6152',
                    800: '#6b4d3a',
                    900: '#553f2d',
                }
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            }
        },
    },

    plugins: [forms],
};