<?php

namespace Core\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PerformCommand
 *
 * Performs single task entry
 *
 * @package Core\QueueBundle\Command
 */
class PerformCommand extends ContainerAwareCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('queue:perform')
            ->addArgument('taskId', InputArgument::REQUIRED)
            ->setDescription('Performs given task.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $taskId = (int)$input->getArgument('taskId');

        $this->getContainer()->get('queue.service')->perform($taskId);
    }
}