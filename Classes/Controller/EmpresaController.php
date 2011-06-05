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
            try {
                $this->empresaRepository->remove($empresa);
            } catch (\Exception $ex) {
                $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
            }
	}
}
 