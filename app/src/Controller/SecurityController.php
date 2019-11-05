<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\CustomAuthenticator;
use App\Specification\PasswordSpecification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PasswordSpecification
     */
    private $passwordSpecification;

    public function __construct(
        UserRepository $userRepository,
        PasswordSpecification $passwordSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->passwordSpecification = $passwordSpecification;
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="app_registration")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param CustomAuthenticator $authenticator
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        CustomAuthenticator $authenticator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $checkUser = $this->userRepository->findOneBy(['email' => $email]);

            if ($checkUser instanceof User) {
                $this->addFlash('error', 'Користувач вже існує.');

                return $this->render('security/registration.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            if (!$this->passwordSpecification->isSatisfiedBy($password)) {
                $this->addFlash('error', 'Пароль має бути не менше 8 символів.');

                return $this->render('security/registration.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            // encode the plain password
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        if ($form['password']->getErrors(true)->count()) {
            $this->addFlash('error', 'Паролі не співпадають.');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
