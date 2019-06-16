<?php declare(strict_types = 1);

namespace App\Command;

use App\Message\ImportMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportCommand extends Command
{
    private const OPERATORS_ARG = 'operators';
    protected static $defaultName = 'app:import-tours';

    /** @var MessageBusInterface */
    private $messageBus;

    /**
     * ImportCommand constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;

        parent::__construct();
    }

    /** @inheritdoc */
    protected function configure()
    {
        $this->setDescription('init tour from operators')
             ->addArgument(
                 self::OPERATORS_ARG,
                 InputArgument::IS_ARRAY|InputArgument::REQUIRED,
                 'Operators tours you want to import (separate multiple names with a space)'
             );
    }

    /** @inheritdoc */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($input->getArgument(self::OPERATORS_ARG) as $operator) {
            $this->messageBus->dispatch(new ImportMessage($operator));
        }

        $output->writeln("done");
    }

}
