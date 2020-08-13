<?php


namespace Teebb\CoreBundle\Controller\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Event\UserEvents;
use Teebb\CoreBundle\Form\Type\Security\UserLoginFormType;
use Teebb\CoreBundle\Form\Type\Security\UserRegisterFormType;
use Teebb\CoreBundle\Form\Type\Security\UserResettingRequestFormType;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher, TemplateRegistry $templateRegistry)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
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

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $event = new UserEvents($user);
            $this->eventDispatcher->dispatch($event, UserEvents::REGISTER_USER_SUCCESS);

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.user.register_success', ['%email%' => $user->getEmail()]
            ));
        }

        return $this->render($this->templateRegistry->getTemplate('register', 'security'), [
            'register_form' => $registerForm->createView()
        ]);
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
            /**@var User $user**/
            $user = $userRepo->findUserByEmail($email);

            if ($user){
                $event = new UserEvents($user);
                $this->eventDispatcher->dispatch($event, UserEvents::RESETTING_SEND_EMAIL);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.user.reset_email_send', ['%email%' => $email]
                ));

            }else{
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
     */
    public function resettingAction(Request $request)
    {

    }

    /**
     * 用户退出方法 留空
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {
    }

}