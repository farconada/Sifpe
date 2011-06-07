<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\TypeConverters;
/**
 * @author Fernando Arconada fernando.arconada@gmail.com
 * Date: 31/05/11
 * Time: 8:49
 */
 
/**
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope singleton
 */
class JsonToEntityConverter extends \F3\FLOW3\Property\TypeConverter\AbstractTypeConverter {
    private $forcedTargetType;

    public function __construct($targetType='') {
        if($targetType){
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
	public function injectPersistenceManager(\F3\FLOW3\Persistence\PersistenceManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

    /**
	 * @param \F3\FLOW3\Reflection\ReflectionService $reflectionService
	 * @return void
	 */
	public function injectReflectionService(\F3\FLOW3\Reflection\ReflectionService $reflectionService) {
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
        
        if(is_string($source)) {
            $source = json_decode($source,true);
        }
        $entity = $this->persistenceManager->getObjectByIdentifier($source['id'], $targetType);
        if(!$entity) {
            $entity = new $targetType();
        }
        if(is_array($source)) {
                $entity = $this->hydrateObjectWhithArray($source, $entity);
        }
        return $entity;
    }

    private function hydrateObjectWhithArray($sourceArray, $targetObject)
    {
        $classSchema = $this->reflectionService->getClassSchema($targetObject);
        foreach ($sourceArray as $property => $value) {
            if (\F3\FLOW3\Reflection\ObjectAccess::isPropertySettable($targetObject,$property) ) {
                $propertyMetadata = $classSchema->getProperty($property);
                if(preg_match('/Domain\\\\Model\\\\/',$propertyMetadata['type'])) {
                    $relatedEntity = $this->persistenceManager->getObjectByIdentifier($value, $propertyMetadata['type']);
                    $value = $relatedEntity;
                }
                \F3\FLOW3\Reflection\ObjectAccess::setProperty($targetObject,$property,$value);
            }
        }
        return $targetObject;
    }


}