<?php

namespace Core\QueueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * Task is a representation of a JobInterface.
 * It contains serialized JobInterface inside `job` column
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="Core\QueueBundle\Entity\Repository\DoctrineTaskRepository")
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var JobInterface
     *
     * @ORM\Column(name="job", type="text")
     */
    private $job;

    /**
     * @var boolean|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = null;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private $completedAt = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_locked", type="boolean")
     */
    private $isLocked = false;

    public function __construct(JobInterface $job)
    {
        $this->setJob($job);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     * @return Task
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime|null
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return unserialize(base64_decode($this->job));
    }

    /**
     * @param JobInterface $job
     */
    public function setJob(JobInterface $job)
    {
        $this->job = base64_encode(serialize($job));
    }

    /**
     * @return bool|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    /**
     * Lock task
     *
     * @return $this
     */
    public function lock()
    {
        $this->isLocked = true;

        return $this;
    }

    /**
     * Unlock task
     *
     * @return $this
     */
    public function unlock()
    {
        $this->isLocked = false;

        return $this;
    }
}
