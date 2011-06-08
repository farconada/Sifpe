<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class EmpresaController extends AbstractController {

    /**
	 * @inject
	 * @var \F3\Sifpe\Domain\Repository\EmpresaRepository
	 */
	protected $empresaRepository;

    public function initializeAction() {
        if (isset($this->arguments['empresa'])) {
            $this->arguments->getArgument('empresa')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Empresa'));
        }
        parent::initializeAction();
    }


    public function listAction() {
            $empresas = $this->empresaRepository->findAll();
            $this->view->assign('total',$empresas->count());
            $this->view->assign('data',$empresas);
	}

}
 