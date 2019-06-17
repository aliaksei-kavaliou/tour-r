<?php declare(strict_types = 1);

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ImportedEvent extends Event
{
    /** @var string */
    private $fileName;

    /** @var string */
    private $operatorName;

    /**
     * ImportedEvent constructor.
     *
     * @param string $fileName
     * @param string $operatorName
     */
    public function __construct(string $fileName, string $operatorName)
    {
        $this->fileName = $fileName;
        $this->operatorName = $operatorName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getOperatorName(): string
    {
        return $this->operatorName;
    }
}
