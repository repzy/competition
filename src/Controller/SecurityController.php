<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\ResettingChangeType;
use App\Form\ResettingRequestType;
use App\Repository\UserRepository;
use App\Security\CustomAuthenticator;
use App\Service\MailerService;
use App\Specification\PasswordSpecification;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * SecurityController constructor.
     * @param UserRepository $userRepository
     * @param PasswordSpecification $passwordSpecification
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerService $mailerService
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordSpecification $passwordSpecification,
        UserPasswordEncoderInterface $passwordEncoder,
        MailerService $mailerService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordSpecification = $passwordSpecification;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailerService = $mailerService;
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
     * @Route("/profile/password/change", name="profile_password_change")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();
            $newPassword = $form->get('new_password')->getData();

            if ($this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Пароль успішно оновлено.');
            } else {
                $this->addFlash('error', 'Старий пароль не вірний.');
            }
        }

        return $this->render('security/change_password.html.twig', [
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

    /**
     * @Route("/resetting/request", name="resetting_request")
     *
     * @param Request $request
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function resettingRequest(Request $request): Response
    {
        $form = $this->createForm(ResettingRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if ($user instanceof User) {
                $resettingCode = Uuid::uuid4()->toString();
                $user->setResettingCode($resettingCode);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->mailerService->sendResettingMessage($user->getEmail(), $this->generateUrl('resetting_change', [
                    'code' => $resettingCode
                ], UrlGeneratorInterface::ABSOLUTE_URL));

                $this->addFlash('success', 'На ваш емейл надіслано повідомлення з посиланням для відновлення паролю.');
            } else {
                $this->addFlash('error', 'Користувача не знайдено.');
            }
        }

        return $this->render('security/resetting/request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/resetting/change/{code}", name="resetting_change")
     *
     * @param Request $request
     * @param string $code
     * @return Response
     */
    public function resettingChange(Request $request, string $code): Response
    {
        $form = $this->createForm(ResettingChangeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $user = $this->userRepository->findOneBy(['resettingCode' => $code]);

            if ($user instanceof User) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));
                $user->setResettingCode(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Пароль успішно змінено.');
            } else {
                $this->addFlash('error', 'Користувача не знайдено.');
            }
        }

        return $this->render('security/resetting/change.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
