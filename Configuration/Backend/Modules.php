<?php


use ITZBund\GsbCore\Controller\ReportController;

return [
    'system_reports' => [
        'parent' => 'system',
        'access' => 'admin',
        'path' => '/module/system/reports',
        'iconIdentifier' => 'module-reports',
        'labels' => 'LLL:EXT:reports/Resources/Private/Language/locallang.xlf',
        'routes' => [
            '_default' => [
                'target' => ReportController::class . '::handleRequest',
            ],
        ],
    ],
];


