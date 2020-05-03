<?php

namespace App\Service;

use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{
    public const EMAIL_FROM = 'bzfstu@gmail.com';

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        Swift_Mailer $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param string $email
     * @param string $url
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendResettingMessage(string $email, string $url): void
    {
        $message = (new Swift_Message('БЗСТУ - Відновлення паролю'))
            ->setFrom(self::EMAIL_FROM)
            ->setTo($email)
            ->setBody(
                $this->twig->render(
                    'emails/resetting.html.twig',
                    ['url' => $url]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
