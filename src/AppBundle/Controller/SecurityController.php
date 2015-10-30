<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SecurityController extends Controller {

    /**
     * @Route("/registerForm", name="security_registerForm")
     * @Template()
     */
    public function registerFormAction() {
        return [];
    }

    /**
     * @Route("/register", name="security_register")
     * @Method("POST")
     */
    public function registerAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $request = Request::createFromGlobals();
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $ico = trim($request->request->get('ico'));

        $user = new User();

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $user->setEmail($email);
        $user->setIco($ico);
        $backgroundImages = ['animal', 'corn', 'farming', 'chicken'];
        shuffle($backgroundImages);
        $user->setBackgroundImage($backgroundImages[0]);

        $profileImages = ['animal', 'corn', 'farming', 'chicken'];
        shuffle($profileImages);
        $user->setProfileImage($profileImages[0]);

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);


        //rabbit MQ
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('ico_queue', false, false, false, false);

        $json = json_encode(["userId" => $user->getId(), "ico" => $ico]);

        $msg = new AMQPMessage($json);
        $channel->basic_publish($msg, '', 'ico_queue');

        dump(" [x] Sent '$ico'\n");
        $channel->close();
        $connection->close();



        return $this->redirect($this->generateUrl('main_overview'));
    }

    /**
     * @Route("/login", name="security_loginForm")
     */
    public function loginFormAction() {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        'AppBundle::Security/loginForm.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction() {
        throw new Exception('This should never be reached!');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction() {
        throw new Exception('This should never be reached!');
    }

}
