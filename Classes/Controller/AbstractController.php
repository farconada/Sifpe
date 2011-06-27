<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

/**
 * Clase abstracta con todos los metodos comunes
 *
 * @abstract
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
class AbstractController extends \F3\FLOW3\MVC\Controller\ActionController
{
    /**
     * Repositorio de objetos de entidad gestionados por esta clase
     *
     * @var \F3\FLOW3\Persistence\Repository
     */
    protected $entityRepository;

    /**
     * Devuelve un objeto de tipo vista en funcion del formato de la peticion, para JSON devuelve una vista JsonView
     *
     * @return \F3\FLOW3\Object\The|\F3\Fluid\View\ViewInterface|object
     */
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


    /**
     * Overrides, Esta funcion para ponerla en un Try-Catch para controlar las que excepciones que puedan lanzar los posibles TypeConverters
     */
    protected function mapRequestArgumentsToControllerArguments()
    {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $ex) {
            $this->forward('error', NULL, NULL, array('msg' => $ex->getMessage()));
        }
    }

    /**
     * Muestra los errores, por defecto en formato JSON
     *
     * @param string $msg
     */
    public function errorAction($msg = '')
    {
        if(get_class($this->view) == 'F3\Fluid\View\TemplateView') {
            $this->view->setTemplatePathAndFilename('resource://'.$this->controllerContext->getRequest()->getControllerPackageKey().'/Private/Templates/Error.html');
        }
        $this->view->assign('value', array(
                                          'success' => FALSE,
                                          'msg' => $msg
                                     ));
    }

    /**
     * Accion basica que por convenio suele ser la de por defecto, y en formato HTML
     *
     * @return string
     */
    public function indexAction()
    {

    }

    /**
     * Elimina un objeto gestionado del repositorio
     *
     * @param F3\Sifpe\Domain\EntityInterface $entity El obejeto a eliminar
     * @dontvalidate $entity
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\EntityInterface $entity)
    {
        try {
            //emit signal
            $this->emitRecordPreDeleted($entity);
            
            $this->persistenceManager->remove($entity);
            $this->persistenceManager->persistAll();
            $this->view->assign('value', array(
                                              'success' => TRUE,
                                              'msg' => 'Borrado'
                                         ));

        } catch (\Exception $ex) {
            $this->forward('error', NULL, NULL, array('msg' => $ex->getMessage()));
        }
    }

    /**
     * Guarda un objeto en el repositorio, si el objeto tiene un ID (mediante objeto->getId()) se actualiza,
     * si el Id esta vacio crea un nuevo objeto
     *
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
        $this->persistenceManager->persistAll();
        $this->view->assign('value', array(
                                          'success' => TRUE,
                                          'msg' => 'Guardado'
                                     ));
    }

    /**
     * Lista los objeto gestionados por el repositorio, por defecto lista todos
     * Se devuelve el listado y el total de objetos devueltos
     */
    public function listAction()
    {
        $items = $this->entityRepository->findAll();
        $output = $this->getOutputArray($items);
        $this->view->assign('value', $output);
    }


    /**
     * A partir de un objeto de resultado de una query devuelve este resultado en forma de array
     * Si el objeto de resultado tiene un metodo 'toArray' se emplea ese metodo, si no se devuelve directamente ese objeto dentro del array.
     * array ( 'total' => 5,
     *          'data' => array(objeto|array, objeto|array ... )
     * Normalmente este array se emplea dentro de una vista JsonView
     *
     * @param \F3\FLOW3\Persistence\QueryResultInterface $items
     * @return array
     */
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

    /**
     * @param \F3\Sifpe\Domain\EntityInterface $entity
     * @return void
     * @signal
     */
    protected function emitRecordPreDeleted(\F3\Sifpe\Domain\EntityInterface $entity) {}
}