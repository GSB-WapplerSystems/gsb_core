<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

return [
    'frontend' => [
        'itzbund/site-extender' => [
            'target' => \ITZBund\GsbCore\Middleware\SiteExtender::class,
            'after' => [
                'typo3/cms-frontend/site',
            ],
        ],
        'itzbund/gsb-version' => [
            'target' => \ITZBund\GsbCore\Middleware\VersionEndpoint::class,
            'before' => [
                'typo3/cms-frontend/site',
            ],
        ],
    ],
];
