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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Settings form type.
 */
class MenuContentProviderSettingsType extends AbstractType
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
            ->add('parent', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Page'
            ))
            ->add('page', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Page'
            ))
            ->add('section', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Section'
            ))
            ->add('order', IntegerType::class, array('mapped' => false))
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
            'data_class' => \Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings::class,
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'menu_content_provider_settings';
    }
}
