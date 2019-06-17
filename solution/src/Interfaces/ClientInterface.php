<?php declare(strict_types = 1);

namespace App\Interfaces;

interface ClientInterface
{
    /**
     * @return string
     */
    public function loadData(): string;
}
