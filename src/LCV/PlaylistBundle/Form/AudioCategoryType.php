<?php

namespace LCV\PlaylistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AudioCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
                    'label' => 'Nom :',
                    'required'=> true,
                    ))
            ->add('save','submit',array(
                    'label' => 'Enregistrer'
             ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\PlaylistBundle\Entity\AudioCategory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_platformbundle_audio_category';
    }
}
