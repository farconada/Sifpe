<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Controller;

/*
* Gasto
*
* Gestion de Gastos
*/
class GastoController extends ApunteController
{
    /**
     * @inject
     * @var \F3\Sifpe\Domain\Repository\GastoRepository
     */
    protected $entityRepository;

    public function initializeAction()
    {
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('F3\Sifpe\Model\Gasto'));
        }
        parent::initializeAction();
    }

}