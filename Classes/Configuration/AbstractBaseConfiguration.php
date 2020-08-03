<?php
declare(strict_types=1);

namespace ITplusX\FlexiblePages\Configuration;


use ITplusX\FlexiblePages\Configuration\Exceptions\InvalidConfigurationException;
use ITplusX\FlexiblePages\Configuration\Validation\ValidationResult;

abstract class AbstractBaseConfiguration
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * AbstractBaseConfiguration constructor.
     *
     * @param array $configuration
     * @throws InvalidConfigurationException
     */
    public function __construct(array $configuration)
    {
        if (static::validate($configuration)->hasErrors()) {
            // TODO: This is crappy. Do it better!!!
            $errorsAsString = implode("\n", static::validate($configuration)->getErrors());

            throw new InvalidConfigurationException("Invalid configuration.\n" . $errorsAsString);
        }
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param array $configuration
     * @return ValidationResult
     */
    abstract public static function validate(array $configuration): ValidationResult;

    /**
     * Returns true if the configuration is valid.
     *
     * @param array $configuration
     * @return bool
     */
    public static function isValid(array $configuration): bool
    {
        return static::validate($configuration)->hasErrors();
    }
}
