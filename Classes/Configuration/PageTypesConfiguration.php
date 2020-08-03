<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Configuration;

use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;

class PageTypesConfiguration extends AbstractBaseConfiguration
{
    /**
     * @var array
     */
    private $pageTypes;

    /**
     * @return array
     * @throws InvalidConfigurationException
     */
    public function getPageTypes(): array
    {
        if (!(is_array($this->pageTypes) && count($this->pageTypes) > 0)) {
            $this->pageTypes = [];

            /**
             * @var int $dokType
             * @var array $pageTypeConfiguration
             */
            foreach ($this->configuration as $dokType => $pageTypeConfiguration) {
                try {
                    $pageTypeConfiguration['dokType'] = $dokType;
                    $pageTypeConfiguration = new PageTypeConfiguration($pageTypeConfiguration);
                } catch (InvalidConfigurationException $exception) {
                    throw new InvalidConfigurationException('Invalid configuration of dokType ' . $dokType);
                }

                $this->pageTypes[$dokType] = $pageTypeConfiguration->getPageType();
            }
        }

        return $this->pageTypes;
    }

    public static function validate(array $configuration): ValidationResult
    {
        // TODO: Validate if doktypes are int
        return new ValidationResult();
    }
}
