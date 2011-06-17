<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;
 
/*
 * IngresoController
 *
 * Gestion de Ingresos
 */
class IngresoController extends ApunteController {
 
    /**
     * @inject
     * @var \F3\Sifpe\Domain\Repository\IngresoRepository
     */
    protected $entityRepository;

    /**
     * Establece los TypeConverter necesarios para que los parametros pasados como JSON sean convertidos a
     * objetos de tipo Domain\Model\Ingreso
     *
     * @return void
     */
    public function initializeAction()
    {
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Ingreso'));
        }
        parent::initializeAction();
    }
 
}