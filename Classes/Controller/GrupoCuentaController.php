<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class GrupoCuentaController extends AbstractController {

    /**
	 * @inject
	 * @var \F3\Sifpe\Domain\Repository\GrupoCuentaRepository
	 */
	protected $grupocuentaRepository;

    public function initializeAction() {
        if (isset($this->arguments['grupocuenta'])) {
            $this->arguments->getArgument('grupocuenta')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter());
        }
        parent::initializeAction();
    }

	/**
	 * List action for this controller.
	 *
	 * @return string
	 */
	public function indexAction() {
	}

    public function listAction() {
            $grupocuentas = $this->grupocuentaRepository->findAll();
            $this->view->assign('total',$grupocuentas->count());
            $this->view->assign('data',$grupocuentas);
	}

    /**
     * @param \F3\Sifpe\Domain\Model\GrupoCuenta $grupocuenta
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\Model\GrupoCuenta $grupocuenta) {
        if($grupocuenta->getId() != '') {
            $this->grupocuentaRepository->update($grupocuenta);
        } else {
            $this->grupocuentaRepository->add($grupocuenta);
        }
	}

    /**
     * @param \F3\Sifpe\Domain\Model\GrupoCuenta $grupocuenta
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\Model\GrupoCuenta $grupocuenta) {
            try {
                $this->grupocuentaRepository->remove($grupocuenta);
            } catch (\Exception $ex) {
                $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
            }
	}
}
 