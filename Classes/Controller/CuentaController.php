<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class CuentaController extends AbstractController {

    /**
	 * @inject
	 * @var \F3\Sifpe\Domain\Repository\CuentaRepository
	 */
	protected $cuentaRepository;

    public function initializeAction() {
        if (isset($this->arguments['cuenta'])) {
            $this->arguments->getArgument('cuenta')
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
            $cuentas = $this->cuentaRepository->findAll();
            $this->view->assign('total',$cuentas->count());
            $this->view->assign('data',$cuentas);
	}

    /**
     * @param \F3\Sifpe\Domain\Model\Cuenta $cuenta
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\Model\Cuenta $cuenta) {
        if($cuenta->getId() != '') {
            $this->cuentaRepository->update($cuenta);
        } else {
            $this->cuentaRepository->add($cuenta);
        }
	}

    /**
     * @param \F3\Sifpe\Domain\Model\Cuenta $cuenta
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\Model\Cuenta $cuenta) {
            try {
                $this->cuentaRepository->remove($cuenta);
            } catch (\Exception $ex) {
                $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
            }
	}
}
 