<?php


namespace Teebb\CoreBundle\Controller\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\Token;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Event\UserEvents;
use Teebb\CoreBundle\Form\Type\Security\UserLoginFormType;
use Teebb\CoreBundle\Form\Type\Security\UserRegisterFormType;
use Teebb\CoreBundle\Form\Type\Security\UserResettingFormType;
use Teebb\CoreBundle\Form\Type\Security\UserResettingRequestFormType;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher, TemplateRegistry $templateRegistry,
                                RouterInterface $router, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->router = $router;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * 用户登录
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        $loginForm = $this->createForm(UserLoginFormType::class);

        return $this->render($this->templateRegistry->getTemplate('login', 'security'), [
            'login_form' => $loginForm->createView(),
            'error' => $error,
        ]);
    }

    /**
     * 用户注册
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $registerForm = $this->createForm(UserRegisterFormType::class);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            /**@var User $user * */
            $user = $registerForm->getData();
            //获取注册用户组对象，并将注册用户设置为注册用户组
            $groupRepo = $this->entityManager->getRepository(Group::class);
            $registerGroup = $groupRepo->findOneBy(['groupAlias'=> 'user']);

            $user->addGroup($registerGroup);
            $user->setRoles($registerGroup->getRoles());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $event = new UserEvents($user);
            //注册成功事件
            $this->eventDispatcher->dispatch($event, UserEvents::REGISTER_USER_SUCCESS);
            //发送激活邮件
            $this->eventDispatcher->dispatch($event, UserEvents::SEND_ACTIVE_MESSAGE);

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.user.register_success', ['%email%' => $user->getEmail()]
            ));
        }

        return $this->render($this->templateRegistry->getTemplate('register', 'security'), [
            'register_form' => $registerForm->createView()
        ]);
    }

    /**
     * 激活用户action
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function activeAccountAction(Request $request)
    {
        $token = $request->get('token');
        $tokenRepo = $this->entityManager->getRepository(Token::class);

        $registerToken = $tokenRepo->findOneBy(['token' => $token]);

        //token已过期，则提示过期
        if ($registerToken->getExpiredAt() <= new \DateTime()) {
            $this->addFlash('danger', $this->container->get('translator')->trans('teebb.core.user.active_account_token_expired', [
                '%resend_active_email%' => $this->router->generate('teebb_user_resend_active_message', ['token' => $token])
            ]));
        } else {
            //激活账号 使用token过期失效 跳转到登录页面
            /**@var User $user * */
            $user = $registerToken->getUser();
            $user->setEnabled(true);

            $registerToken->setExpiredAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->persist($registerToken);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.user.account_active_success'));

            return $this->redirectToRoute('teebb_user_login');
        }

        return $this->render($this->templateRegistry->getTemplate('register_confirm', 'security'), []);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function resendActiveAccountMessage(Request $request)
    {
        $token = $request->get('token');
        $tokenRepo = $this->entityManager->getRepository(Token::class);

        $registerToken = $tokenRepo->findOneBy(['token' => $token]);

        /**@var User $user * */
        $user = $registerToken->getUser();

        //重新发送邮件
        $event = new UserEvents($user);
        $this->eventDispatcher->dispatch($event, UserEvents::SEND_ACTIVE_MESSAGE);

        $this->addFlash('success', $this->container->get('translator')->trans(
            'teebb.core.user.resend_active_success', ['%email%' => $user->getEmail()]
        ));

        return $this->redirectToRoute('teebb_user_login');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resettingRequestAction(Request $request)
    {
        $resettingRequestForm = $this->createForm(UserResettingRequestFormType::class);

        $resettingRequestForm->handleRequest($request);
        if ($resettingRequestForm->isSubmitted() && $resettingRequestForm->isValid()) {
            $formData = $resettingRequestForm->getData();

            $email = $formData['email'];
            $userRepo = $this->entityManager->getRepository(User::class);
            /**@var User $user * */
            $user = $userRepo->findUserByEmail($email);

            if ($user) {
                $event = new UserEvents($user);
                $this->eventDispatcher->dispatch($event, UserEvents::RESETTING_SEND_MESSAGE);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.user.reset_email_send', ['%email%' => $email]
                ));

            } else {
                $this->addFlash('danger', $this->container->get('translator')->trans(
                    'teebb.core.user.email_not_exist', ['%email%' => $email]
                ));
            }
        }

        return $this->render($this->templateRegistry->getTemplate('resetting_request', 'security'), [
            'resetting_request_form' => $resettingRequestForm->createView()
        ]);
    }

    /**
     * 用户重置密码页面
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function resettingAction(Request $request)
    {
        $token = $request->get('token');
        $tokenRepo = $this->entityManager->getRepository(Token::class);

        $resettingToken = $tokenRepo->findOneBy(['token' => $token]);

        //token已过期，则提示过期
        if ($resettingToken->getExpiredAt() <= new \DateTime()) {
            $this->addFlash('danger', $this->container->get('translator')->trans('teebb.core.user.resetting_account_token_expired'));
            return $this->redirectToRoute('teebb_user_resetting_request');
        }

        /**@var User $user * */
        $user = $resettingToken->getUser();
        //添加重置密码表单及处理表单提交并使token过期失效
        $resetPasswordForm = $this->createForm(UserResettingFormType::class);
        $resetPasswordForm->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            //保存新密码，使token过期
            $newPasswordData = $resetPasswordForm->getData();
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $newPasswordData['plainPassword']));

            $resettingToken->setExpiredAt(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->persist($resettingToken);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.user.resetting_account_success'));

            return $this->redirectToRoute('teebb_user_login');
        }

        return $this->render($this->templateRegistry->getTemplate('resetting', 'security'), [
            'user' => $user,
            'resetting_form' => $resetPasswordForm->createView(),
        ]);
    }

    /**
     * 用户退出方法 留空
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {
    }

}