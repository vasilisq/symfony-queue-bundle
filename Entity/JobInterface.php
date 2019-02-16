<?php

namespace Core\QueueBundle\Entity;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface JobInterface
{
    /**
     * Should perform all the things here and return result
     *
     * @param ContainerInterface $container
     * @return bool
     */
    public function perform(ContainerInterface $container): bool;
}