<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityManagerInterface;
use Imagine\Exception\RuntimeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Teebb\CoreBundle\Entity\FileManaged;
use Teebb\CoreBundle\Repository\FileManagedRepository;

class FileEntityValueType extends AbstractType
{
    /**
     * @var FileManagedRepository
     */
    private $fileManagedRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->fileManagedRepository = $entityManager->getRepository(FileManaged::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
            $fileManaged = $event->getData();
            if ($fileManaged && $fileManaged instanceof FileManaged){
                $event->setData($fileManaged);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
            $fileId = $event->getData();
            if ($fileId !== null && !empty($fileId)){
                $fileManaged = $this->fileManagedRepository->find($fileId);
                if (null == $fileManaged){
                    throw new RuntimeException('Can not find the file, Don\'t hack the html code!!!');
                }
                $event->setData($fileManaged);
            }
        });
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}