<?php


namespace Teebb\CoreBundle\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Teebb\CoreBundle\Entity\User;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    public const LOGIN_ROUTE = 'teebb_user_login';

    use TargetPathTrait;

    private $entityManager;
    private $csrfTokenManager;
    private $passwordEncoder;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var User
     */
    private $user;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'plainPassword' => $request->request->get('plainPassword'),
            '_token' => $request->request->get('_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('login_token', $credentials['_token']);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        $this->user = $user;

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['plainPassword']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        //更新当前用户最后登录时间
        $this->user->setLastLogin(new \DateTime());
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        //如果request有redirect_url则跳转到对应页面
        $redirectUrl = $request->get('redirect_url', '');
        if ($redirectUrl !== '') {
            return new RedirectResponse($redirectUrl);
        }

        return new RedirectResponse($this->router->generate('teebb_user_login'));
    }

}