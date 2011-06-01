<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class EmpresaController extends \F3\FLOW3\MVC\Controller\ActionController {

    /**
	 * @inject
	 * @var \F3\Sifpe\Domain\Repository\EmpresaRepository
	 */
	protected $empresaRepository;

    public function initializeAction() {
        if (isset($this->arguments['empresa'])) {
            $this->arguments->getArgument('empresa')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter());
        }
    }

    protected function mapRequestArgumentsToControllerArguments() {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $ex) {
            $this->forward('error');
        }
    }

    public function errorAction() {
        
    }
	/**
	 * List action for this controller.
	 *
	 * @return string
	 */
	public function indexAction() {
            $this->view->assign('empresas',$this->empresaRepository->findAll());
	}

    public function listAction() {
            $empresas = $this->empresaRepository->findAll();
            $this->view->assign('total',$empresas->count());
            $this->view->assign('data',$empresas);
	}

    /**
     * @param \F3\Sifpe\Domain\Model\Empresa $empresa
     * @return void
     */
    public function saveAction(\F3\Sifpe\Domain\Model\Empresa $empresa) {
        if($empresa->getId() != '') {
            $this->empresaRepository->update($empresa);
        } else {
            $this->empresaRepository->add($empresa);
        }
	}

    /**
     * @param \F3\Sifpe\Domain\Model\Empresa $empresa
     * @return void
     */
    public function deleteAction(\F3\Sifpe\Domain\Model\Empresa $empresa) {
            $this->view->assign('empresas',$this->empresaRepository->findAll());
	}
}
 