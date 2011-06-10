<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

class AbstractController extends \F3\FLOW3\MVC\Controller\ActionController
{
    /**
     * @var \F3\FLOW3\Persistence\Repository
     */
    protected $entityRepository;

    protected function resolveView()
    {
        if ($this->request->getformat() == 'json') {
            $view = $this->objectManager->create('\F3\FLOW3\MVC\View\JsonView');
            $view->setControllerContext($this->controllerContext);
            return $view;
        } else {
            return parent::resolveView();
        }

    }


    protected function mapRequestArgumentsToControllerArguments()
    {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $ex) {
            $this->forward('error', NULL, NULL, array('msg' => $ex->getMessage()));
        }
    }

    /**
     * @param string $msg
     */
    public function errorAction($msg = '')
    {
        $this->view->assign('value', array(
                                          'success' => FALSE,
                                          'msg' => $msg
                                     ));
    }

    /**
     * List action for this controller.
     *
     * @return string
     */
    public function indexAction()
    {

    }

    /**
     * @param F3\Sifpe\Domain\EntityInterface $entity
     * @dontvalidate $entity
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\EntityInterface $entity)
    {
        try {
            $this->persistenceManager->remove($entity);
            $this->view->assign('value', array(
                                              'success' => TRUE,
                                              'msg' => 'Borrado'
                                         ));
        } catch (\Exception $ex) {
            $this->forward('error', NULL, NULL, array('msg' => $ex->getMessage()));
        }
    }

    /**
     * @param F3\Sifpe\Domain\EntityInterface $entity
     * @dontvalidate $entity
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\EntityInterface $entity)
    {
        if ($entity->getId() != '') {
            $this->persistenceManager->merge($entity);
        } else {
            $this->persistenceManager->add($entity);
        }
        $this->view->assign('value', array(
                                          'success' => TRUE,
                                          'msg' => 'Guardado'
                                     ));
    }

    public function listAction()
    {
        $items = $this->getItemsToList();
        $output = $this->getOutputArray($items);
        $this->view->assign('value', $output);
    }

    /**
     * @param \F3\FLOW3\Persistence\Doctrine\Query $query
     * @return \F3\FLOW3\Persistence\QueryResultInterface
     */
    protected function getItemsToList(\F3\FLOW3\Persistence\Doctrine\Query $query = NULL)
    {
        if ($query) {
            return $query->execute();
        }
        return $this->entityRepository->findAll();
    }

    protected function getOutputArray(\F3\FLOW3\Persistence\QueryResultInterface $items)
    {
        $output['total'] = $items->count();
        if (method_exists($items->getFirst(), 'toArray')) {
            foreach ($items as $oneItem) {
                $outputArray[] = $oneItem->toArray();
            }
            $output['data'] = $outputArray;
        } else {
            $output['data'] = $items;
        }
        return $output;
    }
}