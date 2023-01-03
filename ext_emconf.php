<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Flexible Pages',
    'description' => 'Supports the setup and management of custom page types.',
    'category' => 'be',
    'version' => '2.2.1',
    'state' => 'stable',
    'author' => 'ITplusX',
    'author_email' => 'mail@itplusx.de',
    'author_company' => 'ITplusX GmbH',
    'clearCacheOnLoad' => 1,
    'constraints' => [
        'depends' => [
            'php' => '7.2.0-8.0.99',
            'typo3' => '10.0.0-11.5.99',
            'fluid_styled_content' => '10.0.0-11.5.99'
        ],
        'conflicts' => [],
        'suggests' => [
            'headless' => '2.0.0-2.99.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'ITplusX\\FlexiblePages\\' => 'Classes'
        ],
    ],
];
