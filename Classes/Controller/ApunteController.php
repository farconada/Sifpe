<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

/**
 * Clase abstracta con todos los metodos comunes para los apuntes, es decir Gastos e Ingresos
 *
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 * @abstract/media/57DA-1EAC/sifpe3.sql
 */
class ApunteController extends AbstractController
{

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
        $items = $this->entityRepository->findApuntesDelMes($start);
        $output = $this->getOutputArray($items);
        $output['totalMeses'] = $this->entityRepository->getTotalMesesRegistrados();
        $this->view->assign('value', $output);
    }

}