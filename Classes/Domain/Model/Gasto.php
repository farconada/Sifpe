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
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
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