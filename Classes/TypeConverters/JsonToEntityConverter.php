<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\TypeConverters;
/**
 * Esta clase se encarga de transformar parametros pasados como JSON a objetos de tipo Domain\Model\XXX
 * si en el constructor se especifica un string con el nombre de la clase (cualificado con el namespace) al que se
 * intentara forzar esa transfomacion a ese tipo de objeto, si no se especifica se empleara el objeto especificado en el
 * signature del metodo de Action para el que se esa este TypeConverter
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope singleton
 * @author Fernando Arconada
 */
class JsonToEntityConverter extends \F3\FLOW3\Property\TypeConverter\AbstractTypeConverter
{
    /**
     * @var string Clase a la que intentar forzar la transformacion
     */
    private $forcedTargetType;

    /**
     * Constructor
     *
     * @param string $targetType nombre de la clase (cualificado con el namespace) al que se intentara forzar la tranformacion
     */
    public function __construct($targetType = '')
    {
        if ($targetType) {
            $this->forcedTargetType = $targetType;
        }
    }

    /**
     * @var \F3\FLOW3\Reflection\ReflectionService
     */
    protected $reflectionService;

    /**
     * @var \F3\FLOW3\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @param \F3\FLOW3\Persistence\PersistenceManagerInterface $persistenceManager
     * @return void
     */
    public function injectPersistenceManager(\F3\FLOW3\Persistence\PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param \F3\FLOW3\Reflection\ReflectionService $reflectionService
     * @return void
     */
    public function injectReflectionService(\F3\FLOW3\Reflection\ReflectionService $reflectionService)
    {
        $this->reflectionService = $reflectionService;
    }

    /**
     * Actually convert from $sourceArray to $targetType, taking into account the fully
     * built $subProperties and $configuration.
     *
     * The return value can be one of three types:
     * - an arbitrary object, or a simple type (which has been created while mapping).
     *   This is the normal case.
     * - NULL, indicating that this object should *not* be mapped (i.e. a "File Upload" Converter could return NULL if no file has been uploaded, and a silent failure should occur.
     * - An instance of \F3\FLOW3\Error\Error -- This will be a user-visible error message lateron.
     * Furthermore, it should throw an Exception if an unexpected failure occured or a configuration issue happened.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $subProperties
     * @param \F3\FLOW3\Property\PropertyMappingConfigurationInterface $configuration
     * @return mixed the target type
     * @api
     */
    public function convertFrom($source, $targetType, array $subProperties = array(), \F3\FLOW3\Property\PropertyMappingConfigurationInterface $configuration = NULL)
    {
        if ($this->forcedTargetType) {
            $targetType = $this->forcedTargetType;
        }

        if (is_string($source)) {
            $source = json_decode($source, true);
        }
        $entity = $this->persistenceManager->getObjectByIdentifier($source['id'], $targetType);
        if (!$entity) {
            $entity = new $targetType();
        }
        if (is_array($source)) {
            $entity = $this->hydrateObjectWhithArray($source, $entity);
        }
        return $entity;
    }

    /**
     * Devuelve un objeto $targetObject rellenado usando los elementos del array especificado como $source
     * Para rellenar los objeto se utilizan los setter del objeto
     * Se el objeto de destino tiene un setter que es otro objeto Domain\Model relacionado entonces hay que buscar primero
     * el objeto relacionado a partir del Id pasado en las propiedades del array asociativo
     *
     * @param  array $sourceArray array asociativo con las propiedades que se usaran para rellar el objeto destino
     * @param  object $targetObject objeto que sive de base para rellenar el objeto resultado
     * @return object
     */
    private function hydrateObjectWhithArray($sourceArray, $targetObject)
    {
        $classSchema = $this->reflectionService->getClassSchema($targetObject);
        foreach ($sourceArray as $property => $value) {
            if (\F3\FLOW3\Reflection\ObjectAccess::isPropertySettable($targetObject, $property)) {
                $propertyMetadata = $classSchema->getProperty($property);
                if (preg_match('/DateTime/', $propertyMetadata['type'])) {
                    $value = new \DateTime($value);
                } else {
                    if (preg_match('/Domain\\\\Model\\\\/', $propertyMetadata['type'])) {
                        $relatedEntity = $this->persistenceManager->getObjectByIdentifier($value, $propertyMetadata['type']);
                        $value = $relatedEntity;
                    }
                }
                \F3\FLOW3\Reflection\ObjectAccess::setProperty($targetObject, $property, $value);
            }
        }
        return $targetObject;
    }


}