<?php


namespace Teebb\CoreBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * 用于构建表单
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface FormContractorInterface
{
    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface;

    /**
     * @param string $name
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormBuilderInterface
     */
    public function getFormBuilder(string $name, string $type = FormType::class, $data = null, $options = []): FormBuilderInterface;

    /**
     * 创建内容实体类型表单
     * @param FormBuilderInterface $formBuilder
     * @param string $entity
     * @param FormRowMarkup[] $formRows
     * @param string $bundle
     * @return FormInterface
     */
    public function buildEntityTypeForm(FormBuilderInterface $formBuilder, string $entity, array $formRows, string $bundle): FormInterface;


    /**
     * 生成删除表单
     * @param string $formName
     * @param string $formType
     * @param mixed $data
     * @param array $options
     * @return FormInterface
     */
    public function generateDeleteForm(string $formName, string $formType, $data, array $options = ['allow_extra_fields' => true]): FormInterface;

}