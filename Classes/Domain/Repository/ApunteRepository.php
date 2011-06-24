<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Domain\Repository;

/**
 * ApunteRepository
 *
 *
 *
 */

class ApunteRepository extends \F3\FLOW3\Persistence\Repository
{
    /**
     * @inject
     * @var \F3\FLOW3\Object\ObjectManagerInterface
     */
    protected $objectManager;


    /**
     * Devuelve los Apuntes de un mes ya sea el mes actual (por defecto) o X meses atras desde este mes
     * Se tienen en cuenta los meses enteros de mes a mes y no de dia a dia,
     * Si hoy es 14-06-2011 1 mes atras devolveria los apuntes de 1 al 31 de Mayo
     *
     * @param int $mesesAtras Numero de meses atras para los que devolver el listado
     * @return \Doctrine_Collection
     */
    public function findPorMes($mesesAtras = 0)
    {
        $mesesAtras = $mesesAtras + 0;
        $fechaInicial = new \DateTime("first day of $mesesAtras month ago");
        $fechaFinal = new \DateTime("last day of $mesesAtras month ago");

        $query = $this->createQuery();
        $query = $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('fecha', $fechaInicial),
                $query->lessThanOrEqual('fecha', $fechaFinal)
            )
        );

        return $query->execute();
    }

    /**
     * Devuelve un entero que representa el numero de meses que hay registrados en la base de datos desde hoy
     *
     * @return int
     */
    public function getTotalMesesRegistrados()
    {
        $query = $this->createQuery();
        $primerApunte = $query->setOrderings(array('fecha' => \F3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING))->setLimit(1)->execute()->getFirst();
        $ultimoApunte = $query->setOrderings(array('fecha' => \F3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING))->setLimit(1)->execute()->getFirst();
        $dateInterval = $primerApunte->getFecha()->diff($ultimoApunte->getFecha());
        $mesesRegistrados = ($dateInterval->y * 12) + $dateInterval->m;

        return $mesesRegistrados;
    }

    /**
     * Devuelve una lista de cuentas con el total por cuenta de cada mes
     *
     * @param int $mesesAtras Numero de meses atras para los que devolver el listado
     * @return array
     */
    public function getTotalCuentasMensual($mesesAtras = 0)
    {
        $mesesAtras = $mesesAtras + 0;
        $fechaInicial = new \DateTime("first day of $mesesAtras month ago");
        $fechaFinal = new \DateTime("last day of $mesesAtras month ago");

        $entityManagerFactory = $this->objectManager->get('\F3\FLOW3\Persistence\Doctrine\EntityManagerFactory');
        $entityManager = $entityManagerFactory->create();
        $query = $entityManager->createQuery('SELECT sum(g.cantidad) AS cantidad, c.name AS cuenta FROM ' . $this->objectType . ' g JOIN g.cuenta c WHERE g.fecha <=:fechaFinal AND g.fecha >=:fechaInicial GROUP BY c.id');

        return $query->execute(array('fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
    }

    /**
     * Devuelve el total de los apuntes de un mes para los doces meses del año de enero a dicimbre
     *
     * @param  $aniosAtras Mumero de años atras para los que devolver el listado
     * @return array
     */
    public function getResumenAnual($aniosAtras)
    {
        $fechaInicial = new \DateTime('-' . $aniosAtras . ' year');
        $fechaInicial->setDate($fechaInicial->format('Y'), 1, 1);
        $fechaFinal = new \DateTime('-' . $aniosAtras . ' year');
        $fechaFinal->setDate($fechaFinal->format('Y'), 1, 1);
        $result = array();
        for ($i = 0; $i < 12; $i++) {
            $result[$i]['mes'] = $fechaInicial->format('M');
            $fechaFinal->add(new \DateInterval('P0Y1M'));
            $entityManagerFactory = $this->objectManager->get('\F3\FLOW3\Persistence\Doctrine\EntityManagerFactory');
            $entityManager = $entityManagerFactory->create();
            $query = $entityManager->createQuery('SELECT sum(g.cantidad) AS cantidad FROM ' . $this->objectType . ' g WHERE g.fecha <:fechaFinal AND g.fecha >=:fechaInicial');

            $res = $query->execute(array('fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
            $result[$i]['cantidad'] = $res[0]['cantidad'];
            $fechaInicial->add(new \DateInterval('P0Y1M'));
        }
        return $result;

    }

    public function getResumenMes($mesesAtras)
    {
        $fechaInicial = new \DateTime("first day of $mesesAtras month ago");
        $fechaFinal = new \DateTime("last day of $mesesAtras month ago");
        $result = array();

        $entityManagerFactory = $this->objectManager->get('\F3\FLOW3\Persistence\Doctrine\EntityManagerFactory');
        $entityManager = $entityManagerFactory->create();
        $query = $entityManager->createQuery('SELECT sum(g.cantidad) AS cantidad FROM \F3\Sifpe\Domain\Model\Gasto g WHERE g.fecha <:fechaFinal AND g.fecha >=:fechaInicial');
        $res = $query->execute(array('fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
        $result['gastos'] = $res[0]['cantidad'] ? $res[0]['cantidad'] : 0;
        $query = $entityManager->createQuery('SELECT sum(g.cantidad) AS cantidad FROM \F3\Sifpe\Domain\Model\Ingreso g WHERE g.fecha <:fechaFinal AND g.fecha >=:fechaInicial');
        $res = $query->execute(array('fechaInicial' => $fechaInicial, 'fechaFinal' => $fechaFinal));
        $result['ingresos'] = $res[0]['cantidad'] ? $res[0]['cantidad'] : 0;
        return $result;

    }
}