<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "imageoptimizer"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'ImageOptimizer',
    'description' => 'Optimize uploaded/processed images with binaries of your choice',
    'category' => 'misc',
    'author' => 'Christoph Lehmann',
    'author_email' => 'post@christophlehmann.eu',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-8.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
