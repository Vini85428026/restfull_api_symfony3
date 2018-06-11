<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 10/06/18
 * Time: 23:11
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Exception\ValidationException;
use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HumansController extends AbstractController
{
    use ControllerTrait;

    /**
     * @Rest\View()
     */
    public function getHumansAction()
    {
        $movies  = $this->getDoctrine()->getRepository('AppBundle:Person')->findAll();

        return $movies;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("person", converter="fos_rest.request_body")
     * @Rest\NoRoute()
     */
    public function postHumansAction(Person $person, ConstraintViolationListInterface $validationErrors)
    {
        if(count($validationErrors) > 0){
            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return $person;
    }

    /**
     * @Rest\View()
     */
    public function deleteHumanAction(Person $person)
    {
        if($person == null){
            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
    }

    /**
     * @Rest\View()
     */
    public function getHumanAction(Person $person)
    {
        if($person == null){
            return $this->view(null, 404);
        }

        return $person;
    }

}