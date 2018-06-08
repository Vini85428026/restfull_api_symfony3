<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 08/06/18
 * Time: 14:45
 */

namespace AppBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends HttpException
{
    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $message = [];

        /** @var ConstraintViolationInterface $violation */
        foreach($constraintViolationList as $violation){
            $message[$violation->getPropertyPath()] = $violation->getMessage();
        }

        parent::__construct(400, json_encode($message));
    }
}