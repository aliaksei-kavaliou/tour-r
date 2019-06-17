<?php declare(strict_types = 1);

namespace App\Service;

use App\Interfaces\ClientInterface;

class DummyClient implements ClientInterface
{

    /**
     * @return string
     */
    public function loadData(): string
    {
        return '{"foo": "bar"}';
    }
}
