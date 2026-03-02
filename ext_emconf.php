<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'ImageOptimizer',
    'description' => 'Optimize uploaded/processed images with binaries of your choice',
    'category' => 'misc',
    'author' => 'Christoph Lehmann',
    'author_email' => 'post@christophlehmann.eu',
    'state' => 'stable',
    'version' => '6.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.1.0-14.3.99',
        ],
    ],
];
