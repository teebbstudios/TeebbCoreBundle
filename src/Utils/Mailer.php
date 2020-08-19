<?php


namespace Teebb\CoreBundle\Utils;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\Entity\Token;
use Teebb\CoreBundle\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Address
     */
    private $fromAddress;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager,
                                RouterInterface $router, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->translator = $translator;

        $this->fromAddress = new Address('qww.zone@aliyun.com', 'admin');
    }

    /**
     * 发送注册激活邮件
     * @param User $user
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function sendRegisterActiveMessage(User $user)
    {
        //生成激活token
        $token = new Token($user);
        $token->setDescription('register');
        $token->setExpiredAt(new \DateTime('+1 day'));

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $activeUrl = $this->router->generate('teebb_user_active', ['token' => $token->getToken()], RouterInterface::ABSOLUTE_URL);

        $toAddress = new Address($user->getEmail(), $user->getUsername());
        $subject = $this->translator->trans('teebb.core.user.account_active_subject');
        $textBody = $this->translator->trans('teebb.core.user.account_active_body', [
            '%username%' => $user->getUsername(),
            '%activeUrl%' => $activeUrl
        ]);
        $htmlBody = nl2br($textBody);

        $this->sendSimpleEmail($this->fromAddress, $toAddress, $subject, $textBody, $htmlBody);
    }

    /**
     * @param User $user
     * @throws TransportExceptionInterface
     */
    public function sendResettingMessage(User $user)
    {
        //生成重置密码token
        $token = new Token($user);
        $token->setDescription('resetting');
        $token->setExpiredAt(new \DateTime('+1 day'));

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $resettingUrl = $this->router->generate('teebb_user_resetting', ['token' => $token->getToken()], RouterInterface::ABSOLUTE_URL);

        $toAddress = new Address($user->getEmail(), $user->getUsername());
        $subject = $this->translator->trans('teebb.core.user.resetting_subject');
        $textBody = $this->translator->trans('teebb.core.user.resetting_body', [
            '%username%' => $user->getUsername(),
            '%resettingUrl%' => $resettingUrl
        ]);
        $htmlBody = nl2br($textBody);

        $this->sendSimpleEmail($this->fromAddress, $toAddress, $subject, $textBody, $htmlBody);
    }


    /**
     * 发送普通邮件
     * @param Address $fromAddress
     * @param Address $toAddress
     * @param string $subject
     * @param string $textBody
     * @param string $htmlBody
     * @throws TransportExceptionInterface
     */
    private function sendSimpleEmail(Address $fromAddress, Address $toAddress, string $subject, string $textBody, string $htmlBody)
    {
        $email = (new Email())
            ->from($fromAddress)
            ->to($toAddress)
            ->subject($subject)
            ->text($textBody)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}