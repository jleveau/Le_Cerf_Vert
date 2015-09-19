<?php

namespace LCV\PlaylistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use LCV\PlaylistBundle\Entity\PlaylistRepository;
use LCV\PlaylistBundle\Form\AudioFileType;
use LCV\PlaylistBundle\Form\DataTransformer\FileToAudio;

class PlaylistAudioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              
          $builder
           ->add('image', 'sonata_media_type', array(
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'audio_image',
                 'required' => false
            ))
          ->add('title','text', array(
                      'label' => ' ',
                      'required' => true))
          ->add('sortOrder')
          ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\PlaylistBundle\Entity\PlaylistAudio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_playlistbundle_playlistaudio';
    }
    
}
