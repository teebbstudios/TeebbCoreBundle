<?php


namespace Teebb\CoreBundle\Form\Type;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\User;

/**
 * Todo: ckeditor 工具栏需要根据过滤器的更改而更改。新版本再增加
 * CKEDITOR编辑器
 */
class TextFormatTextareaType extends AbstractType
{
    /**
     * @var User $currentUser
     */
    private $currentUser;

    public function __construct(Security $security)
    {
        /**@var User $currentUser * */
        $this->currentUser = $security->getUser();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'config_name' => $this->choiceTheBestConfig($this->currentUser)
        ]);
    }

    public function getParent()
    {
        return CKEditorType::class;
    }

    /**
     * @param User $user
     * @return mixed|string
     */
    private function choiceTheBestConfig(User $user)
    {
        $ckeditorConfigs = [];

        $userGroups = $user->getGroups();

        foreach ($userGroups as $userGroup) {
            $ckeditorConfigs[] = $userGroup->getCkeditorConfig();
        }

        $ckeditorConfigs = array_unique($ckeditorConfigs);

        if (!in_array('full', $ckeditorConfigs))
        {
            return $ckeditorConfigs[0];
        }

        return 'full';
    }
}