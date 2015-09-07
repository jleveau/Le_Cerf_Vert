<?php

namespace LCV\PlaylistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use LCV\PlaylistBundle\Entity\PlaylistRepository;
use LCV\PlaylistBundle\Form\AudioFileType;
use LCV\PlaylistBundle\Form\DataTransformer\FileToAudio;

class PlaylistType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder
          ->add('title',     'text',array(
                 'label'  => "Titre :",
                 ))
                      
          ->add('category', 'entity', array(
              'label'  => "Categorie :",
              'class'    => 'LCVPlaylistBundle:PlaylistCategory',
              'property' => 'name'))
                      
          ->add('save',      'submit', array(
                'label'  => "Créer la playlist",
                'attr'   =>  array ('class' => "btn btn-primary")))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\PlaylistBundle\Entity\Playlist'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_playlistbundle_playlist';
    }
    
    
}
