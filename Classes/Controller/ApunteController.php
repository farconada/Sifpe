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
     * indexAction
     * @return void
     */
    public function indexAction()
    {
        $apuntes = $this->entityRepository->findAll();
        $this->view->assign('apuntes',$apuntes);
    }

}