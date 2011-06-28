<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

interface DoctrineEventListenerInterface {
    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $args);
    public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $args);
    public function preRemove(\Doctrine\ORM\Event\LifecycleEventArgs $args);
}