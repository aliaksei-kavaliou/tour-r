<?php declare(strict_types = 1);

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ImportFailedEvent extends Event
{
    /** @var string */
    private $operatorName;

    /**
     * ImportFailedEvent constructor.
     *
     * @param string $operatorName
     */
    public function __construct(string $operatorName)
    {
        $this->operatorName = $operatorName;
    }

    /**
     * @return string
     */
    public function getOperatorName(): string
    {
        return $this->operatorName;
    }
}
