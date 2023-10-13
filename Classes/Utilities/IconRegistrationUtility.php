<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Utilities;

use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IconRegistrationUtility
{
    /**
     * Registers a list of icons to be available inside the Icon Factory
     *
     * @param array $icons
     *
     * @return array
     */
    public static function registerIcons(array $icons): array
    {
        $registeredIcons = [];

        foreach ($icons as $identifier => $fileReference) {
            $registeredIcons[self::registerIcon($fileReference, $identifier)] = $fileReference;
        }

        return $registeredIcons;
    }

    /**
     * Registers an icon to be available inside the Icon Factory and returns its identifier
     *
     * If no custom $identifier is provided, an identifier is generated from the filename
     * of the $iconReference.
     *
     * @param string $iconReference
     * @param string $iconIdentifier Optional icon identifier for icon registry
     * @param bool $overwriteExisting
     *
     * @return string Identifier of registered icon
     */
    public static function registerIcon(
        string $iconReference,
        string $iconIdentifier = '',
        bool $overwriteExisting = false
    ): string {
        if ($iconIdentifier === '' || is_numeric($iconIdentifier)) {
            $iconIdentifier = self::convertFilenameToIdentifier($iconReference);
        }

        if (!$overwriteExisting && self::isRegistered($iconIdentifier)) {
            return '';
        }

        /** @var IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $iconProvider = $iconRegistry->detectIconProvider($iconReference);
        $iconRegistry->registerIcon($iconIdentifier, $iconProvider, ['source' => $iconReference]);

        return $iconIdentifier;
    }

    /**
     * Returns the filename converted to the common pattern of an icons identifier
     *
     * @param $filenameOrPath
     *
     * @return mixed
     */
    public static function convertFilenameToIdentifier($filenameOrPath)
    {
        $ext = pathinfo($filenameOrPath, PATHINFO_EXTENSION);

        return 'tx-flexiblepages-' . str_replace(
            '_',
            '-',
            GeneralUtility::camelCaseToLowerCaseUnderscored(basename($filenameOrPath, '.' . $ext))
        );
    }

    /**
     * Returns true if the $iconIdentifier exists already.
     *
     * @param $iconIdentifier
     * @return bool
     */
    public static function isRegistered($iconIdentifier): bool
    {
        /** @var IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        return $iconRegistry->isRegistered($iconIdentifier);
    }
}
