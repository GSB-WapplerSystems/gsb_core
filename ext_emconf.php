<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'GSB Template',
    'description'      => 'Basic Templateset',
    'category'         => 'distribution',
    'constraints'      => [
        'depends'   => [
            'typo3' => '12.0.0-12.9.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    'autoload'         => [
        'psr-4' => [
            'ITZBund\\GsbTemplate\\' => 'Classes',
        ],
    ],
    'state'            => 'stable',
    'author'           => 'ITZBund',
    'author_email'     => 'no-reply@itzbund.de',
    'author_company'   => 'ITZBund',
    'version'          => '1.0.0',
    'clearCacheOnLoad' => true,
];
