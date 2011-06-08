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
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Cuenta'));
        }
        parent::initializeAction();
    }


    public function listAction() {
            $cuentas = $this->cuentaRepository->findAll();
            $this->view->assign('total',$cuentas->count());
            $this->view->assign('data',$cuentas);
	}

}
 