<?php

return [
    'format' => 'png',
    'size' => 250,
    'margin' => 2,
    'error_correction' => 'H',
    
    // FORZAR GD COMO BACKEND
    'renderer' => 'gd', // Esto fuerza a usar GD
    
    'styles' => [
        'square' => \SimpleSoftwareIO\QrCode\Renderer\GD\Square::class,
        'circle' => \SimpleSoftwareIO\QrCode\Renderer\GD\Circle::class,
    ],
    
    'renderer_options' => [
        'gd' => [
            // Opciones específicas de GD
        ],
    ],
];