<?php

namespace Masev\SettingsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter;

class SiteaccessType extends AbstractType
{

    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter
     */
    private $router;

    public function __construct(ChainRouter $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'SiteaccessType';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate('masev_ajax_form'))
            ->setMethod('POST')
            ->add('siteaccess', ChoiceType::class, [
                'label' => 'Select siteaccess',
                'label_attr' => [
                    'class' => 'col-sm col-form-label'
                ],
                'placeholder' => 'Listes des sites',
                'required' => true,
                'choices' => array_flip($options['siteaccess_list']),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            "siteaccess_list"   => []
        ));
    }
}

