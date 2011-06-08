<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;

/**
 * Una cuenta
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @Table(name="cuenta")
 * @entity
 */
class Cuenta implements \F3\Sifpe\Domain\EntityInterface {
    /**
	 * id
	 *
	 * @var integer
     * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue
	 */
	protected $id = '';

    /**
	 * nombre
	 *
	 * @var string
	 * @validate Text, StringLength(minimum = 1, maximum = 80)
	 * @Column(length="80")
	 */
	protected $name = '';

    /**
	 * @var \F3\Sifpe\Domain\Model\GrupoCuenta
	 * @ManyToOne
     * @JoinColumn(name="grupo_cuenta_id", referencedColumnName="id")
	 */
	protected $grupo;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \F3\Sifpe\Domain\Model\GrupoCuenta $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return \F3\Sifpe\Domain\Model\GrupoCuenta
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

}