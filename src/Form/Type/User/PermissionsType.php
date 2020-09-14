<?php


namespace Teebb\CoreBundle\Form\Type\User;


use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Teebb\CoreBundle\Voter\TeebbVoterInterface;


/**
 * 权限表单
 */
class PermissionsType extends AbstractType
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->container = $container;
    }

    private function getAllVoterPermissions()
    {
        $voterServiceIds = $this->parameterBag->get('teebb.core.voter.services');

        $permissions = [];
        foreach ($voterServiceIds as $voterServiceId) {
            /**@var TeebbVoterInterface $voterService * */
            $voterService = $this->container->get($voterServiceId);

            $permissions[] = $voterService->getVoteOptionArray();
        }

        return $permissions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = $this->getAllVoterPermissions();

        $builder
            ->add('permission', ChoiceType::class, [
                'label' => false,
                'choices' => $permissions,
                'expanded' => true,
                'multiple' => true,
            ]);
    }

}