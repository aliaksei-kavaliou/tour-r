<?php declare(strict_types = 1);

namespace App\Tests\integration\Command;

use App\Command\AwsInitCommand;
use App\Kernel;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;
use Aws\Sqs\SqsClient;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AwsInitCommandTest extends KernelTestCase
{
    private const EXISTED_BUCKET = "EXISTED_BUCKET";
    private const FOO_BUCKET = "FOO_BUCKET";
    private const FOO_QUEUE = "FOO_QUEUE";

    /** @var array */
    private $envVars;

    /** @var SqsClient */
    private $sqsClient;

    /** @var S3Client */
    private $s3Client;

    protected function setUp()
    {
        $this->sqsClient = $this->prophesize(SqsClient::class);
        $this->s3Client = $this->prophesize(S3Client::class);

        $this->envVars = \getenv();
        $this->purgeEnv();

        \putenv('FOO_S3_BUCKET=' . self::FOO_BUCKET);
        \putenv('FOO_SQS_QUEUE=' . self::FOO_QUEUE);
        \putenv('EXISTED_S3_BUCKET=' . self::EXISTED_BUCKET);
    }

    public function testExecuteProdEnv(): void
    {
        $tester = $this->getCommandTester('prod');
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();

        $this->assertEquals(1, $tester->getStatusCode());
        $this->assertContains('Command can be run in DEV environment only', $display);
    }

    public function testExecute(): void
    {
        $this->s3Client->doesBucketExist(self::FOO_BUCKET)->shouldBeCalled()->willReturn(false);
        $this->s3Client->doesBucketExist(self::EXISTED_BUCKET)->shouldBeCalled()->willreturn(true);
        $this->s3Client->createBucket(['Bucket' => self::FOO_BUCKET])->shouldBeCalled();

        $this->sqsClient->createQueue(['QueueName' => self::FOO_QUEUE])->shouldBeCalled()->willReturn(
            new Result(['QueueUrl' => 'http://localstack:4576/queue/' . self::FOO_QUEUE])
        );

        $tester = $this->getCommandTester();
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();

        $this->assertEquals(0, $tester->getStatusCode());
        $this->assertContains('Infrastructure created', $display);
        $this->assertContains(self::FOO_QUEUE . ' queue created', $display);
        $this->assertContains(self::EXISTED_BUCKET . ' bucket already exists. Skipped.', $display);
        $this->assertContains(self::FOO_BUCKET . ' bucket created', $display);
    }

    public function testExecutePossibleExceptions(): void
    {
        $this->s3Client->doesBucketExist(Argument::type('string'))->willReturn(false);
        $this->s3Client->createBucket(Argument::type("array"))->willThrow(AwsException::class);
        $this->sqsClient->createQueue(Argument::type("array"))->willThrow(AwsException::class);

        $tester = $this->getCommandTester();
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();
        $this->assertNotContains(self::FOO_QUEUE . ' queue created', $display);
        $this->assertNotContains(self::FOO_BUCKET . ' bucket created', $display);
        $this->assertContains('Finished with errors', $display);
    }

    private function getCommandTester(string $kernelEnv = 'dev'): CommandTester
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = new AwsInitCommand($this->s3Client->reveal(), $this->sqsClient->reveal(), $kernelEnv);
        $command->setApplication($application);

        return new CommandTester($command);
    }

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function tearDown()
    {
        $this->purgeEnv();

        foreach ($this->envVars as $key => $value) {
            \putenv($key . '=' . $value);
        }

        parent::tearDown();
    }

    protected function purgeEnv(): void
    {
        foreach (\getenv() as $key => $value) {
            \putenv($key);
        }
    }
}
