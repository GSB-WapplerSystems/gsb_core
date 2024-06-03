<?php

// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

return [
    \ITZBund\GsbCore\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'title' => [
                'fieldName' => 'title',
            ],
        ],
    ],
];
