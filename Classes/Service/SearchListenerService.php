<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

/**
 * Clase que se escuchar eventos de Doctrine y lanzar la indexacion de los objetos de entidad
 *
 * @link http://www.doctrine-project.org/docs/orm/2.0/en/reference/events.html
 * @link http://framework.zend.com/manual/en/zend.search.lucene.html
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 * @scope prototype
 */
class SearchListenerService implements DoctrineEventListenerInterface
{

    /**
     * Objeto que indexa objeto es Lucene
     *
     * @var \F3\Sifpe\Service\LuceneIndexedSearchService
     * @inject
     */
    protected $indexManager;

    /**
     * The postPersist event occurs for an entity after the entity has been made persistent.
     *
     * It will be invoked after the database insert operations. Generated primary key values are available in the postPersist event
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return void
     */
    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();

            $this->indexManager->updateApunteIndex($apunte);
        }
    }

    /**
     * The postUpdate event occurs after the database update operations to entity data. It is not called for a DQL UPDATE statement.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return void
     */
    public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();

            $this->indexManager->updateApunteIndex($apunte);
        }
    }

    /**
     * The preRemove event occurs for a given entity before the respective EntityManager remove operation for that entity is executed.
     *
     * It is not called for a DQL DELETE statement.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return void
     */
    public function preRemove(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \F3\Sifpe\Domain\Model\Apunte) {
            $apunte = $args->getEntity();
            
            $this->indexManager->deleteApunteIndex($apunte);
        }
    }

}
