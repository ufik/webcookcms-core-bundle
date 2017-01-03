<?php

/**
 * This file is part of Webcook security bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Page form type.
 */
class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'constraints' => array(
                    new NotBlank(array('message' => 'common.pages.form.title.required')),
                ),
                'label' => 'common.pages.form.name',
            ))
            ->add('layout', TextType::class, array(
                'constraints' => array(
                    new NotBlank(array('message' => 'common.pages.form.layout.required')),
                ),
                'label' => 'common.pages.form.layout',
            ))
            ->add('parent', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Page'
            ))
            ->add('language', EntityType::class, array(
                'class' => 'WebcookCmsI18nBundle:Language'
            ))
            ->add('sections', CollectionType::class, array(
                'entry_type' => PageSectionType::class,
                'allow_add' => true
            ))
            ->add('version', HiddenType::class, array('mapped' => false));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \Webcook\Cms\CoreBundle\Entity\Page::class,
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'page';
    }
}
