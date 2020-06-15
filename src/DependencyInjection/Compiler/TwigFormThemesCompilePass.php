<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemesCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //add bootstrap4 and custom form type theme to form_theme
        $form_theme_old = $container->getParameter('twig.form.resources');
        $form_theme = array_merge($form_theme_old, ['bootstrap_4_layout.html.twig', '@TeebbCore/form/form_types.html.twig']);

        $container->getDefinition('twig.form.engine')->replaceArgument(0, $form_theme);
    }
}