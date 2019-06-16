<?php declare(strict_types = 1);

namespace App\Message;

class ImportMessage
{
    /** @var string */
    private $operatorName;

    /**
     * ImportMessage constructor.
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
