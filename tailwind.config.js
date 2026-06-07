export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Filament/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#8B2C2C',
                accent: '#B45309',
                soft: '#F8F6F4',
                ink: '#1F2937',
            },
            fontFamily: {
                heading: ['Merriweather', 'serif'],
                body: ['Source Sans 3', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
