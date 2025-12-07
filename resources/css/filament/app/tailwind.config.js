import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'custom-50': '#F0F8FF',   // AliceBlue
                'custom-100': '#E0FFFF',  // Azure
                'custom-200': '#AFEEEE',  // PaleTurquoise
                'custom-300': '#7FFFD4',  // Aquamarine
                'custom-400': '#40E0D0',  // Turquoise
                'custom-500': '#48D1CC',  // MediumTurquoise
                'custom-600': '#20B2AA',  // LightSeaGreen
                'custom-700': '#008B8B',  // DarkCyan
                'custom-800': '#008080',  // Teal
                'custom-900': '#005F5F',  // DarkCyan darker shade
            },
            fontFamily: {
                sans: ['Inter', 'MyCustomFont', 'sans-serif'], // Example: 'Inter' or any custom font
            },
        },
    },
}
