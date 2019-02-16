<?php

namespace Core\QueueBundle\Entity\Repository;

use Core\QueueBundle\Entity\JobInterface;
use Core\QueueBundle\Entity\Task;
use Doctrine\ORM\EntityRepository;

/**
 * DoctrineTaskRepository
 */
class DoctrineTaskRepository extends EntityRepository implements TaskRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function get(int $count = 10)
    {
        return $this->findBy(['status' => null, 'isLocked' => false], null, $count);
    }

    /**
     * @inheritdoc
     */
    public function markAsCompleted(Task $task, bool $status)
    {
        $task->setStatus($status);
        $task->setCompletedAt(new \DateTime());
        $task->unlock();
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function enqueue(JobInterface $job)
    {
        $task = new Task($job);
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function findOneById(int $taskId)
    {
        return $this->findOneBy(['id' => $taskId]);
    }

    /**
     * @inheritdoc
     */
    public function lock(Task $task)
    {
        $task->lock();
        $this->_em->persist($task);
        $this->_em->flush();
    }
}
