<?php declare(strict_types = 1);

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AwsInitCommand extends Command
{
    protected static $defaultName = 'app:aws-init';

    /** @var S3Client */
    private $s3Client;

    /** @var string */
    private $kernelEnvironment;

    /**
     * AwsInitCommand constructor.
     *
     * @param S3Client $s3Client
     * @param string   $kernelEnvironment
     */
    public function __construct(S3Client $s3Client, string $kernelEnvironment)
    {
        $this->s3Client = $s3Client;
        $this->kernelEnvironment = $kernelEnvironment;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('init aws s3 infrastructures');
    }


    /** @inheritdoc */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ('dev' !== $this->kernelEnvironment) {
            $io->error('Command can be run in DEV environment only');

            return 1;
        }

        $withErrors = false;

        foreach (\getenv() as $key => $value) {
            if (false !== \strpos($key, 'S3_BUCKET')) {
                $withErrors |= !$this->createBucket($value, $io);
            }
        }

        if ($withErrors) {
            $io->note('Finished with errors.');
        }

        $io->text("Infrastructure created. Bye!\n");

        return 0;
    }

    /**
     * @param string       $value
     * @param SymfonyStyle $io
     *
     * @return bool
     */
    private function createBucket(string $value, SymfonyStyle $io): bool
    {
        if ($this->s3Client->doesBucketExist($value)) {
            $io->note($value . ' bucket already exists. Skipped.');

            return true;
        }

        try {
            $this->s3Client->createBucket(['Bucket' => $value]);
            $io->success($value . ' bucket created.');
        } catch (AwsException $e) {
            $io->error($e->getAwsErrorMessage());

            return false;
        }

        return true;
    }
}
