<?php


namespace Core\QueueBundle\Entity\Repository;

use Core\QueueBundle\Entity\JobInterface;
use Core\QueueBundle\Entity\Task;

interface TaskRepositoryInterface
{
    /**
     * Returns first incomplete task or null
     *
     * @param int $count
     *
     * @return Task|null
     */
    public function get(int $count = 10);

    /**
     * Marks task as completed and unlocks task
     *
     * @param Task $task
     * @param bool $status
     */
    public function markAsCompleted(Task $task, bool $status);

    /**
     * Enqueues job
     *
     * @param JobInterface $job
     */
    public function enqueue(JobInterface $job);

    /**
     * @param int $taskId
     * @return Task|null
     */
    public function findOneById(int $taskId);

    /**
     * Locks task
     *
     * @param Task $task
     * @return void
     */
    public function lock(Task $task);
}