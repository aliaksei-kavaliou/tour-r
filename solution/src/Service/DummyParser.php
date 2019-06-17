<?php declare(strict_types = 1);

namespace App\Service;

use App\Interfaces\ParserInterface;

class DummyParser implements ParserInterface
{

    /**
     * @param string $data
     *
     * @return string
     */
    public function parse(string $data): string
    {
        return \json_encode(["foo" => "bar"]);
    }
}
