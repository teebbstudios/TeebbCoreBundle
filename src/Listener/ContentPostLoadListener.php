<?php


namespace Teebb\CoreBundle\Listener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;

/**
 * Doctrine entity listener
 *
 * 用于内容Entity从数据库中加载时，设置对应的内容类型
 */
class ContentPostLoadListener
{
    public function postLoad(Content $content, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $typesRepository = $em->getRepository(Types::class);
        /**@var Types $type**/
        $type = $typesRepository->findOneBy(['typeAlias' => $content->getTypeAlias()]);
        $content->setType($type);
    }
}