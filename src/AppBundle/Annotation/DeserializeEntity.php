<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 11/06/18
 * Time: 10:13
 */

namespace AppBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class DeserializeEntity
{
    /**
     * @var string
     * @Required()
     */
    public $type;
    /**
     * @var string
     * @Required()
     */
    public $idField;
    /**
     * @var string
     * @Required()
     */
    public $setter;
    /**
     * @var string
     * @Required()
     */
    public $idGetter;
}