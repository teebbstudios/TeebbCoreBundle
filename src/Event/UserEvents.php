<?php


namespace Teebb\CoreBundle\Event;


use Symfony\Contracts\EventDispatcher\Event;
use Teebb\CoreBundle\Entity\User;

final class UserEvents extends Event
{
    //用户注册完成后的事件处理，比如发送激活邮件
    public const REGISTER_USER_SUCCESS = 'register.user.success';

    //发送激活账号邮件
    public const SEND_ACTIVE_MESSAGE = 'send.active.message';

    //发送重置密码邮件
    public const RESETTING_SEND_MESSAGE = 'resetting.send.message';

    /**
     * @var User
     */
    private $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}