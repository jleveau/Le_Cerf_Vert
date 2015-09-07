<?php

namespace LCV\PlaylistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use LCV\PlaylistBundle\Entity\AudioFileRepository;


class AudioFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder
          ->add('file','file',array(
                     'attr'=>array('class' => "form-control"),
                     'label'  => " ",
                     'required' => false)
                     )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\PlaylistBundle\Entity\AudioFile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_playlistbundle_audio_file';
    }
    
    
}
