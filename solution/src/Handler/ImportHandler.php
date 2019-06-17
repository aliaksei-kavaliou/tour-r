<?php declare(strict_types = 1);

namespace App\Handler;

use App\Events\ImportedEvent;
use App\Events\ImportFailedEvent;
use App\Message\ImportMessage;
use App\Service\ClientProvider;
use App\Service\ParserProvider;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportHandler
{
    /** @var ClientProvider */
    private $clientProvider;

    /** @var ParserProvider */
    private $parserProvider;

    /** @var S3Client */
    private $s3Client;

    /** @var string */
    private $storageBucket;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * ImportHandler constructor.
     *
     * @param ClientProvider           $clientProvider
     * @param ParserProvider           $parserProvider
     * @param S3Client                 $s3Client
     * @param string                   $storageBucket
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ClientProvider $clientProvider,
        ParserProvider $parserProvider,
        S3Client $s3Client,
        string $storageBucket,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->clientProvider = $clientProvider;
        $this->parserProvider = $parserProvider;
        $this->s3Client = $s3Client;
        $this->storageBucket = $storageBucket;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ImportMessage $message
     *
     * @throws \App\Exceptions\NoClientException
     * @throws \App\Exceptions\NoParserException
     */
    public function __invoke(ImportMessage $message): void
    {
        $client = $this->clientProvider->getClient($message->getOperatorName());
        $data = $client->loadData();

        if (!$data) {
            return;
        }

        $parser = $this->parserProvider->getParser($message->getOperatorName());
        $parsed = $parser->parse($data);

        $key = $message->getOperatorName() . time() . '.json';

        try {
            $this->s3Client->putObject(
                [
                    'Bucket' => $this->storageBucket,
                    'Key' => $key,
                    'Body' => $parsed,
                ]
            );

        }  catch (S3Exception $e) {
            $this->eventDispatcher->dispatch(new ImportFailedEvent($message->getOperatorName()));

            return;
        }

        $this->eventDispatcher->dispatch(new ImportedEvent($key, $message->getOperatorName()));
    }
}
