<?php
namespace ITplusX\FlexiblePages\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;
use ITplusX\FlexiblePages\Page\Icon;
use ITplusX\FlexiblePages\Utilities\IconRegistrationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IconConfiguration extends AbstractBaseConfiguration
{
    /**
     * @var Icon
     */
    private $icon;

    /**
     * Returns an instance of Icon.
     *
     * The following possibilities of the configuration are being resolved automatically:
     *
     *  1. Icon definition without an identifier
     *  2. Icon definition with existing identifier
     *  3. Icon definition with a custom identifier
     *
     * @return Icon
     * @throws InvalidConfigurationException
     */
    public function getIcon(): Icon
    {
        if (!($this->icon instanceof Icon)) {
            $identifier = '';
            $source = '';

            extract($this->configuration);

            if (static::isCustomConfiguration($this->configuration)) {
                if (static::isExistingIdentifier($identifier)) {
                    throw new InvalidConfigurationException(
                        'Invalid configuration of icon. Identifier ' . $identifier . ' exists already.'
                    );
                }
            } else {
                if (static::isFileReference($source)) {
                    $identifier = IconRegistrationUtility::convertFilenameToIdentifier($source);
                }
            }

            if (empty(trim($identifier))) {
                throw new InvalidConfigurationException('Invalid configuration of icon');
            }

            $this->icon = new Icon($identifier, $source);
        }

        return $this->icon;
    }


    public static function validate(array $configuration): ValidationResult
    {
        $validationResult = new ValidationResult();

        if (!(self::hasValidIdentifier($configuration) || self::hasValidSource($configuration))) {
            $validationResult->addError('Either identifier or source has to be set.');
        }

        return $validationResult;
    }

    /**
     * Returns true if the $this->configuration is recognized as customized.
     *
     * @param array $configuration
     * @return bool
     */
    public static function isCustomConfiguration(array $configuration): bool
    {
        return self::hasValidIdentifier($configuration) && self::hasValidSource($configuration);
    }

    /**
     * Returns true if the configuration is a fileReference.
     *
     * @param string $source
     * @return bool
     */
    public static function isFileReference(string $source): bool
    {
        return GeneralUtility::getFileAbsFileName($source) !== '';
    }

    /**
     * Returns true if the $identifier is registered already.
     *
     * @param string $identifier
     * @return bool
     */
    public static function isExistingIdentifier(string $identifier): bool
    {
        return IconRegistrationUtility::isRegistered($identifier);
    }

    /**
     * Returns true if the $configuration contains a valid identifier configuration.
     *
     * @param array $configuration
     * @return bool
     */
    public static function hasValidIdentifier(array $configuration): bool
    {
        return array_key_exists('identifier', $configuration);
    }

    /**
     * Returns true if the $configuration contains a valid source configuration.
     *
     * @param array $configuration
     * @return bool
     */
    public static function hasValidSource(array $configuration): bool
    {
        return array_key_exists('source', $configuration);
    }
}
