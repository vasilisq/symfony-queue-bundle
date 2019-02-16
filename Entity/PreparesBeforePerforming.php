<?php

namespace Core\QueueBundle\Entity;


use Symfony\Component\DependencyInjection\ContainerInterface;

interface PreparesBeforePerforming
{
    /**
     * Should perform required actions, before it is actually performed
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function prepare(ContainerInterface $container);
}