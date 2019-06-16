<?php declare(strict_types = 1);

namespace App\Service;

use App\Exceptions\NoParserException;
use App\Interfaces\ParserInterface;

class ParserProvider
{
    /** @var ParserInterface[] */
    private $parsers;

    /**
     * @param string          $operatorName
     * @param ParserInterface $parser
     *
     * @return ParserProvider
     */
    public function addParser(string $operatorName, ParserInterface $parser): ParserProvider
    {
        $this->parsers[$operatorName] = $parser;

        return $this;
    }

    /**
     * @param string $operatorName
     *
     * @return ParserInterface
     * @throws NoParserException
     */
    public function getParser(string $operatorName): ParserInterface
    {
        $parser = $this->parsers[$operatorName] ?? null;

        if (!$parser) {
            throw new NoParserException("No parser found for $operatorName $operatorName");
        }

        return $parser;
    }
}
