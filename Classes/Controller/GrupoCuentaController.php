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
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\GrupoCuenta'));
        }
        parent::initializeAction();
    }

    public function listAction() {
            $grupocuentas = $this->grupocuentaRepository->findAll();
            $this->view->assign('total',$grupocuentas->count());
            $this->view->assign('data',$grupocuentas);
	}

}
 