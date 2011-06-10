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
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Cuenta'));
        }
        parent::initializeAction();
    }


    public function listAction() {
            $items = $this->cuentaRepository->findAll();
            $output['total'] = $items->count();
            if( method_exists($items->getFirst(),'toArray')) {
                foreach ($items as $oneItem) {
                    $outputArray[] = $oneItem->toArray();
                }
                $output['data'] = $outputArray;
            } else {
                $output['data'] = $items;
            }
            $this->view->assign('value',$output);
	}

}
 