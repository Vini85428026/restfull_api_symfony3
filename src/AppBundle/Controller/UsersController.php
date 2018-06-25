<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 20/06/18
 * Time: 14:52
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ValidationException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class UsersController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @Rest\View()
     * @Security("is_granted('show', theUser)", message="Access denied")
     */
    public function getUserAction(?User $theUser)
    {
        if (null === $theUser) {
            throw new NotFoundHttpException();
        }

        return $theUser;
    }

    /**
     * @Rest\View(statusCode=201)
     * @Rest\NoRoute()
     * @ParamConverter("user", converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"Deserialize"}}})
     */
    public function postUserAction(
        User $user, ConstraintViolationListInterface $validationErrors
    ) {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            )
        );

        $user->setRoles([User::ROLE_USER]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
