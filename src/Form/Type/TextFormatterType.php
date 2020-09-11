<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;
use Teebb\CoreBundle\Entity\User;
use function Doctrine\ORM\QueryBuilder;

class TextFormatterType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /**@var User $user**/
        $user = $this->security->getUser();

        $resolver->setDefaults([
            'label' => 'teebb.core.form.formatter_name',
            'class' => Formatter::class,
            'query_builder' => function (EntityRepository $er) use ($user) {
                $qb = $er->createQueryBuilder('f');
                $qb ->join('f.groups', 'g')
                    ->where($qb->expr()->in('g.id', ':currentUserGroups'))
                    ->setParameter('currentUserGroups', $user->getGroups())
                ;
                return $qb;
            },
            'choice_label' => 'name',
            'choice_value' => 'alias',
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'row_attr' => [
                'class' => 'form-inline'
            ]
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}