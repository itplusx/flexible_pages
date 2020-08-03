<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Flexible Pages',
    'description' => 'Supports the setup and management of custom page types.',
    'category' => 'be',
    'version' => '1.0.0',
    'state' => 'stable',
    'author' => 'ITplusX',
    'author_email' => 'mail@itplusx.de',
    'author_company' => 'ITplusX GmbH',
    'clearCacheOnLoad' => 1,
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
            'fluid_styled_content' => '9.5.0-9.5.99'
        ],
        'conflicts' => [],
        'suggests' => [
            'headless' => '1.0.0-1.0.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'ITplusX\\FlexiblePages\\' => 'Classes'
        ],
    ],
];
