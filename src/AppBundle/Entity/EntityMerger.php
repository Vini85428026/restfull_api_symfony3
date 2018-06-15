<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 15/06/18
 * Time: 14:34
 */

namespace AppBundle\Entity;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Id;

class EntityMerger
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(AnnotationReader $annotationReader){
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param $entity
     * @param $changes
     */
    public function merge($entity, $changes)
    {
        // Get $entity class name or false if it's not a class
        $entityClassName = get_class($entity);

        if(false === $entityClassName){
            throw new \InvalidArgumentException("$entity is not a class");
        }

        // Get $entity class name or false if it's not a class
        $changeClassName = get_class($changes);

        if(false === $changeClassName){
            throw new \InvalidArgumentException("$changes is not a class");
        }

        //continue only if $change object is of the same class as $entity or $changes is a subclass of $entity
        if(!is_a($changes, $entityClassName)){
            throw new \InvalidArgumentException("Cannot merge object of class $changeClassName with object of class $entityClassName");
        }

        $entityReflection = new \ReflectionObject($entity);
        $changesReflection = new \ReflectionObject($changes);

        foreach ($changesReflection->getProperties() as $changedProperty){
            $changedProperty->setAccessible(true);
            $changePropertyValue = $changedProperty->getValue($changes);

            //ignore properties with null value
            if(null === $changePropertyValue){
                continue;
            }

            //ignore $changes property if it's not present on $entity
            if(!$entityReflection->hasProperty($changedProperty->getName())){
                continue;
            }

            $entityProperty = $entityReflection->getProperty($changedProperty->getName());
            $annotation = $this->annotationReader->getPropertyAnnotation($entityProperty, Id::class);

            //ignore $changes property that has Doctrine @Id annotation
            if(null !== $annotation){
                continue;
            }

            $entityProperty->setAccessible(true);
            $entityProperty->setValue($entity, $changePropertyValue);
        }
    }
}