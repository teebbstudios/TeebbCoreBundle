<?php


namespace Teebb\CoreBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\RouterInterface;

/**
 * 把URL中连字符转为下划符
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class RouteParameterUnderlineFixSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequestEvent',
            ResponseEvent::class => 'onKernelResponseEvent'
        ];
    }

    //将http请求URL中的连字符转为下划线,
    public function onKernelRequestEvent(RequestEvent $event)
    {
        $request = $event->getRequest();
        $requestAttrs = $request->attributes;

        //如果route中有typeAlias参数，则将alias中的连字符转为下划线
        if ($typeAlias = $requestAttrs->get('typeAlias')) {
            $typeAlias = $this->aliasToMachine($typeAlias);
            $requestAttrs->set('typeAlias', $typeAlias);
        }
        if ($fieldAlias = $requestAttrs->get('fieldAlias')) {
            $fieldAlias = $this->aliasToMachine($fieldAlias);
            $requestAttrs->set('fieldAlias', $fieldAlias);
        }
    }

    //如果是RedirectResponse，则获取路径中的变量typeAlias fieldAlias，将下划线转为连字符
    public function onKernelResponseEvent(ResponseEvent $event)
    {
        $response = $event->getResponse();
        if ($response instanceof RedirectResponse) {
            $targetUrl = $response->getTargetUrl();

            if (!strpos($targetUrl, '://')){
                $result = $this->router->match($targetUrl);

                //如果$targetUrl 包含变量: typeAlias 或 fieldAlias，处理url中的下划线转为连字符
                $parameters = [];
                if (isset($result['typeAlias'])) {
                    $result['typeAlias'] = $this->aliasToNormal($result['typeAlias']);
                    $parameters['typeAlias'] = $result['typeAlias'];

                    $response->setTargetUrl($this->router->generate($result['_route'], $parameters));
                }
                if (isset($result['fieldAlias'])) {
                    $result['fieldAlias'] = $this->aliasToNormal($result['fieldAlias']);
                    $parameters['fieldAlias'] = $result['fieldAlias'];

                    $response->setTargetUrl($this->router->generate($result['_route'], $parameters));
                }
            }
        }

    }

    /**
     * 机读别名下划线转成连字符
     * @param string $alias
     * @return string
     */
    protected function aliasToNormal(string $alias): string
    {
        return str_replace('_', '-', $alias);
    }

    /**
     * 机读别名连字符转成下划线
     * @param string $alias
     * @return string
     */
    private function aliasToMachine(string $alias): string
    {
        return str_replace('-', '_', $alias);
    }

}