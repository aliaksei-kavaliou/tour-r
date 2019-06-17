<?php declare(strict_types = 1);

namespace App\Interfaces;

interface ParserInterface
{
    /**
     * @param string $data
     *
     * @return string
     */
    public function parse(string $data): string;
}
