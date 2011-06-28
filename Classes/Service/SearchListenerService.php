<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

/**
 * @scope prototype
 */
class SearchListenerService implements DoctrineEventListenerInterface
{

    protected $index;

    /**
     * @var \F3\Sifpe\Service\LuceneIndexedSearch
     * @inject
     */
    protected $indexManager;


    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();

            $this->updateApunteIndex($apunte);
        }
    }

    public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();

            $this->updateApunteIndex($apunte);
        }
    }


    public function preRemove(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();
            
            $this->deleteApunteIndex($apunte);
        }
    }

}
