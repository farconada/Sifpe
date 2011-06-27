<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;
/**
 * Gasto
 *
 * Un gasto
 */
 
/**
 * @scope prototype
 * @HasLifecycleCallbacks
 * @entity
 * @Table(name="gasto")
 */
class Gasto extends Apunte implements \F3\Sifpe\Domain\EntityInterface {
    /**
	 * @var \F3\Sifpe\Domain\Model\Empresa
	 * @ManyToOne(inversedBy="gastos")
     * @JoinColumn(name="empresa_id", referencedColumnName="id")
	 */
    protected $empresa;

    /**
	 * @var \F3\Sifpe\Domain\Model\Cuenta
	 * @ManyToOne(inversedBy="gastos")
     * @JoinColumn(name="cuenta_id", referencedColumnName="id")
	 */
    protected $cuenta;
}