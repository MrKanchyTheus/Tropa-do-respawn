<?php
/**
 * Head compartilhado - Tropa do Respawn
 * Importação de Tailwind, Google Fonts (Inter, Rajdhani, JetBrains Mono) e estilos gamer.
 */
if (!isset($pageTitle)) {
    $pageTitle = 'Tropa do Respawn | Wiki de Jogos, Guias e Comunidade';
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/jpeg" href="assets/img/logo.jpeg">

    <!-- Google Fonts: Inter, Rajdhani e JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Play CDN com Configuração Customizada -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            950: '#07060b',
                            900: '#0c0a14',
                            850: '#110e1c',
                            800: '#171426',
                            700: '#231e38',
                        },
                        respawn: {
                            purple: '#9333ea',
                            neon: '#a855f7',
                            light: '#c084fc',
                            dark: '#6b21a8',
                            glow: 'rgba(168, 85, 247, 0.35)',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons para temas gamer -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS Customizado -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="bg-dark-950 text-gray-100 flex flex-col min-h-screen selection:bg-purple-600 selection:text-white">
