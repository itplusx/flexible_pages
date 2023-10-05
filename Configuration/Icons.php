<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use ITplusX\FlexiblePages\Utilities\ExtensionConfigurationUtility;

return [
    'tx-flexiblepages-pagelist' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . ExtensionConfigurationUtility::EXTKEY . '/Resources/Public/Icons/tx-flexiblepages-pagelist.svg',
    ],
];
