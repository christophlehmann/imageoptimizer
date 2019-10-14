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
    'version' => '2.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.4.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
