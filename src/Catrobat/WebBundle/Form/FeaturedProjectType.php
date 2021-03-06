<?php

namespace Catrobat\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeaturedProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('program')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Catrobat\CoreBundle\Entity\FeaturedProgram'
        ));
    }

    public function getName()
    {
        return 'catrobat_catrowebbundle_featuredprogramtype';
    }
}
