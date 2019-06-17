<?php declare(strict_types = 1);

namespace App\Service;

use App\Exceptions\NoClientException;
use App\Interfaces\ClientInterface;

class ClientProvider
{
    /** @var ClientInterface[] */
    private $clients;

    /**
     * @param string          $operatorName
     * @param ClientInterface $client
     *
     * @return ClientProvider
     */
    public function addClient(string $operatorName, ClientInterface $client): ClientProvider
    {
        $this->clients[$operatorName] = $client;

        return $this;
    }

    /**
     * @param string $operatorName
     *
     * @return ClientInterface
     * @throws NoClientException
     */
    public function getClient(string $operatorName): ClientInterface
    {
        $client = $this->clients[$operatorName] ?? null;

        if (!$client) {
            throw new NoClientException("No client found for $operatorName operatior");
        }

        return $client;
    }
}
