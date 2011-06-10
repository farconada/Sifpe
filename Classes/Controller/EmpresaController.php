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
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Empresa'));
        }
        parent::initializeAction();
    }


    public function listAction() {
            $items = $this->empresaRepository->findAll();
            $output['total'] = $items->count();
            $output['data'] = $items;
            $this->view->assign('value',$output);
	}

}
 