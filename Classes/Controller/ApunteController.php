<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

/*
* ApunteController
*
* Gestion de apuntes
*/
class ApunteController extends AbstractController
{
    /**
     * @var \F3\FLOW3\Persistence\Repository
     */
    protected $apunteRepository;

    /**
     * indexAction
     * @return void
     */
    public function indexAction()
    {
        $apuntes = $this->apunteRepository->findAll();
        $this->view->assign('apuntes',$apuntes);
    }

}