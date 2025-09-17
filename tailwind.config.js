import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/views/templates/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Allow for CSS custom properties
                primary: 'var(--primary-color, #2563eb)',
                secondary: 'var(--secondary-color, #64748b)',
                accent: 'var(--accent-color, #f59e0b)',
            },
        },
    },

    plugins: [forms],
};
