<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class AbstractController extends \F3\FLOW3\MVC\Controller\ActionController {
    protected function mapRequestArgumentsToControllerArguments() {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $ex) {
            $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
        }
    }

    /**
     * @param string $msg
     */
    public function errorAction($msg='') {
        $this->view->assign('msg',$msg);
    }

    /**
	 * List action for this controller.
	 *
	 * @return string
	 */
	public function indexAction() {
	}

    /**
     * @param F3\Sifpe\Domain\EntityInterface $entity
     * @dontvalidate $entity
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\EntityInterface $entity) {
            try {
                $this->persistenceManager->remove($entity);
            } catch (\Exception $ex) {
                $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
            }
	}

    /**
     * @param F3\Sifpe\Domain\EntityInterface $entity
     * @dontvalidate $entity
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\EntityInterface $entity) {
        if($entity->getId() != '') {
            $this->persistenceManager->merge($entity);
        } else {
            $this->persistenceManager->add($entity);
        }
	}
}