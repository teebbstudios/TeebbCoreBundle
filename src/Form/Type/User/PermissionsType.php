<?php


namespace Teebb\CoreBundle\Form\Type\User;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * 权限表单
 */
class PermissionsType extends AbstractType
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = $this->parameterBag->get('teebb.core.voter.permissions');

        $builder
            ->add('permission', ChoiceType::class, [
                'label' => false,
                'choices' => $permissions,
                'expanded' => true,
                'multiple' => true,
            ]);
    }

}