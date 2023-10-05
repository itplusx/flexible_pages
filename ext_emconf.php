<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Flexible Pages',
    'description' => 'Supports the setup and management of custom page types.',
    'category' => 'be',
    'version' => '3.0.0-dev',
    'state' => 'stable',
    'author' => 'ITplusX',
    'author_email' => 'mail@itplusx.de',
    'author_company' => 'ITplusX GmbH',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.2.99',
            'typo3' => '12.4.0-12.4.99',
            'fluid_styled_content' => '12.4.0-12.4.99'
        ],
        'conflicts' => [],
        'suggests' => [
            'headless' => '4.0.0-4.99.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'ITplusX\\FlexiblePages\\' => 'Classes'
        ],
    ],
];
