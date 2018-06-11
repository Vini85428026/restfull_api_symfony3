<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 11/06/18
 * Time: 10:22
 */

namespace AppBundle\Serializer;


use AppBundle\Annotation\DeserializeEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class DoctrineEntityDeserializationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'format' => 'json'
            ],
            [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
                'format' => 'json'
            ]
        ];
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
          $deserializedType = $event->getType()['name'];

          if(!class_exists($deserializedType)){
              return;
          }

          $data = $event->getData();
          $class = new \ReflectionClass($deserializedType);


          foreach ($class->getProperties() as $property){
              if(!isset($data[$property->name])){
                  continue;
              }

              /** @var DeserializeEntity $annotation */
              $annotation = $this->annotationReader->getPropertyAnnotation(
                  $property,
                  DeserializeEntity::class
              );

              if($annotation == null || !class_exists($annotation->type)){
                  continue;
              }

              $data[$property->name] = [
                  $annotation->idField => $data[$property->name]
              ];
          }

          $event->setData($data);
    }

    public function onPostDeserialize(ObjectEvent $event)
    {

        $deserializedType = $event->getType()['name'];

        if(!class_exists($deserializedType)){
            return;
        }

        $object = $event->getObject();
        $reflection = new \ReflectionObject($object);


        foreach ($reflection->getProperties() as $property){
            /** @var DeserializeEntity $annotation */
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $property,
                DeserializeEntity::class
            );

            if($annotation == null || !class_exists($annotation->type)){
                continue;
            }

            if(!$reflection->hasMethod($annotation->setter)){
                throw new \LogicException(
                  "Object {$reflection->getName()} does not have the {$annotation->setter} method"
                );
            }
        }
    }
}