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
        $this->view->assign('apuntes',$apuntes);    }

    /**
     * @param \F3\Sifpe\Domain\Model\Apunte $apunte
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        try {
            $this->apunteRepository->remove($apunte);
        } catch (\Exception $ex) {
            $this->forward('error', NULL, NULL, array('msg' => $ex->getMessage()));
        }
    }

    /**
     * @param \F3\Sifpe\Domain\Model\Apunte $apunte
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        if ($apunte->getId() != '') {
            $this->apunteRepository->update($apunte);
        } else {
            $this->apunteRepository->add($apunte);
        }
    }

}