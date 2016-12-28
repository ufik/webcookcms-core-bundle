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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Page form type.
 */
class PageSectionType extends AbstractType
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
            ->add('section', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Section',
                'constraints' => array(
                    new NotBlank(array('message' => 'common.pages.form.section.required')),
                ),
                'label' => 'common.pages.form.section',
            ))
            ->add('contentProvider', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:ContentProvider',
                'constraints' => array(
                    new NotBlank(array('message' => 'common.contentProvider.form.layout.required')),
                ),
                'label' => 'common.pages.form.contentProvider',
            ));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \Webcook\Cms\CoreBundle\Entity\PageSection::class,
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pageSection';
    }
}
