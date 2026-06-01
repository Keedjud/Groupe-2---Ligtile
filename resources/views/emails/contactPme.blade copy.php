@tailwind base;
@tailwind components;
@tailwind utilities;

<!DOCTYPE html>
<html class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import 'tailwindcss';
    </style>
</head>
<body class="font-sans bg-light-palette-white text-light-primary dark:bg-dark-palette-black dark:text-dark-primary">
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-violet-600 rounded-t-lg p-6">
            <h2 class="text-h2 font-bold text-light-palette-white">New Contact Message</h2>
        </div>

        <div class="bg-light-palette-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-6">
            <div class="mb-6">
                <span class="block text-text-h5 font-bold text-light-primary dark:text-dark-primary mb-2">Entreprise :</span>
                <p class="text-text-regular text-light-secondary dark:text-dark-secondary">{{ $company_name }}</p>
            </div>

            <div class="mb-6">
                <span class="block text-text-h5 font-bold text-light-primary dark:text-dark-primary mb-2">Email :</span>
                <p class="text-text-regular text-light-secondary dark:text-dark-secondary">{{ $email }}</p>
            </div>

            <div class="mb-6">
                <span class="block text-text-h5 font-bold text-light-primary dark:text-dark-primary mb-2">Email :</span>
                <p class="text-text-regular text-light-secondary dark:text-dark-secondary">{{ $message }}</p>
            </div>


        <div class="bg-violet-100 dark:bg-gray-800 rounded-b-lg p-4 text-center">
            <p class="text-text-small text-light-tertiary dark:text-dark-tertiary">Ceci est un message automatique envoyé depuis la page de prises de rendez-vous des collectes.</p>
        </div>
    </div>
</body>
</html>
