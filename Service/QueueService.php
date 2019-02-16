<?php

namespace Core\QueueBundle\Service;

use Core\QueueBundle\Entity\JobInterface;
use Core\QueueBundle\Entity\PreparesBeforePerforming;
use Core\QueueBundle\Entity\Repository\TaskRepositoryInterface;
use Core\QueueBundle\Entity\Task;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class QueueService
 * @package Core\QueueBundle\Service
 */
class QueueService
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;


    /** @var ContainerInterface */
    private $container;

    /** @var int */
    private $maxPerformingTasks;

    /**
     * QueueService constructor.
     * @param TaskRepositoryInterface $taskRepository
     * @param ContainerInterface $container
     * @param string $maxTasks
     */
    public function __construct(TaskRepositoryInterface $taskRepository, ContainerInterface $container, $maxTasks)
    {
        $this->taskRepository = $taskRepository;
        $this->container = $container;
        $this->maxPerformingTasks = (int)$maxTasks;
    }

    /**
     * Performs given task
     *
     * @param int $taskId
     */
    public function perform($taskId)
    {
        $task = $this->taskRepository->findOneById($taskId);

        if (!$task instanceof Task) {
            return;
        }

        $job = $task->getJob();
        try {
            $status = $job->perform($this->container);
        } catch (\Exception $e) {
            $status = false;
        }

        // Complete task and unlock it
        $this->taskRepository->markAsCompleted($task, $status);
    }

    /**
     * Dispatches some queue threads
     */
    public function serve()
    {
        foreach ($this->taskRepository->get($this->maxPerformingTasks) as $task) {
            /** @var Task $task */
            $command = 'php ' .
                $this->container->getParameter('kernel.root_dir') .
                '/console queue:perform ' .
                $task->getId();

            // Locking task for further queries
            $this->taskRepository->lock($task);

            // Async exec
            shell_exec($command . " > /dev/null 2>/dev/null &");
        }
    }

    /**
     * @param JobInterface $job
     */
    public function enqueue(JobInterface $job)
    {
        // Prepare job if required
        if ($job instanceof PreparesBeforePerforming) {
            $job->prepare($this->container);
        }

        $this->taskRepository->enqueue($job);
    }
}