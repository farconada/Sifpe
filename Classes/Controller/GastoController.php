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
    protected $apunteRepository;

    public function initializeAction()
    {
        if (isset($this->arguments['gasto'])) {
            $this->arguments->getArgument('gasto')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('F3\Sifpe\Model\Gasto'));
        }
        parent::initializeAction();
    }

}