<?php
namespace ITplusX\FlexiblePages\Configuration\Validation;

class ValidationResult
{
    /**
     * @var array
     */
    private $errors = [];


    public function addError(string $message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
