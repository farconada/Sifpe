<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

/**
 * Clase abstracta con todos los metodos comunes para los apuntes, es decir Gastos e Ingresos
 *
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 * @abstract
 */
class ApunteController extends AbstractController
{
    /**
     * @var \F3\Sifpe\Service\DoctrineEventListenerInterface
     * @inject
     */
    protected $doctrineEventListener;

    /**
     * @var \F3\Sifpe\Service\IndexSearchInterface
     * @inject
     */
    protected $indexManager;

    protected function initializeAction()
    {
        parent::initializeAction();

        $entityManagerFactory = $this->objectManager->get('\F3\FLOW3\Persistence\Doctrine\EntityManagerFactory');
        $entityManager = $entityManagerFactory->create();
        $entityManager->getEventManager()->addEventListener(
            array(\Doctrine\ORM\Events::postUpdate, \Doctrine\ORM\Events::postPersist, \Doctrine\ORM\Events::preRemove), $this->doctrineEventListener
        );
        $this->persistenceManager->injectEntityManager($entityManager);

    }


    /**
     * indexAction
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * Lista los objeto gestionados por el repositorio, por defecto lista todos
     * Se devuelve el listado y el total de objetos devueltos
     *
     * @param int $start Numero de meses atras para los que mostrar el listado
     * @return void
     */
    public function listAction($start = 0)
    {
        $items = $this->entityRepository->findPorMes($start);
        $output = $this->getOutputArray($items);
        $output['totalMeses'] = $this->entityRepository->getTotalMesesRegistrados();
        $this->view->assign('value', $output);
    }

    /**
     * @param int $start
     * @return void
     */
    public function listResumenPorCuentaAction($start = 0){
        $cuentasMes = $this->entityRepository->getTotalCuentasMensual($start);
        $cuentasMesAnterior = $this->entityRepository->getTotalCuentasMensual($start + 1);
        $resultado = array();
        foreach ($cuentasMes as $cuenta) {
            $resultado[$cuenta['cuenta']]['cantidad'] = $cuenta['cantidad'];
        }
        foreach ($cuentasMesAnterior as $cuenta) {
            $resultado[$cuenta['cuenta']]['cantidad_anterior'] = $cuenta['cantidad'];
        }
        $i=0;
        $resultadoPlain = array();
        foreach ($resultado as $cuenta => $resultadoItem) {
            $resultadoPlain[$i]['cuenta'] = $cuenta;
            $resultadoPlain[$i]['cantidad'] = isset($resultadoItem['cantidad']) ? $resultadoItem['cantidad']+0 : 0;
            $resultadoPlain[$i]['cantidad_anterior'] = isset($resultadoItem['cantidad_anterior']) ? $resultadoItem['cantidad_anterior']+0 : 0;
            $i++;
        }

        $output['data'] = $resultadoPlain;
        $output['total'] = count($resultado);
        $this->view->assign('value', $output);
    }

    /**
     * @param int $start
     * @return void
     */
    public function listResumenAnualAction($start = 0) {
        $items = $this->entityRepository->getResumenAnual($start);
        $itemsAnterior = $this->entityRepository->getResumenAnual($start+1);
        $i = 0;
        $resultado = array();
        foreach ($items as $mes) {
            $resultado[$i]['mes'] = $mes['mes'];
            $resultado[$i]['cantidad'] = $mes['cantidad'] ? $mes['cantidad'] : 0 ;
            $resultado[$i]['cantidad_anterior'] = $itemsAnterior[$i]['cantidad'] ?  $itemsAnterior[$i]['cantidad']: 0;
            $i++;
        }

        $output['data'] = $resultado;
        $output['total'] = count($resultado);
        $this->view->assign('value', $output);

    }

    /**
     * @param int $start
     * @return void
     */
    public function listResumenMesAction($start = 0){
        $items = $this->entityRepository->getResumenMes($start);
        $output['data'] = $items;
        $this->view->assign('value', $output);
    }

}