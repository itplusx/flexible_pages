<?php
declare(strict_types = 1);

namespace ITplusX\FlexiblePages\Page;

class Icon
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $source;


    /**
     * Icon constructor.
     * @param string $identifier
     * @param string $source
     */
    public function __construct(string $identifier, string $source = null)
    {
        $this->identifier = $identifier;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * TODO: Implement the ArrayObject magic methods to ensure that the Icon acts as an array
     *
     * @return array
     */
    public function toArray() {
        return [$this->identifier => $this->source];
    }
}
