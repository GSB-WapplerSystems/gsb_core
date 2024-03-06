<?php

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
