<?php

namespace LCV\PlaylistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use LCV\PlaylistBundle\Entity\PlaylistRepository;
use LCV\PlaylistBundle\Form\AudioFileType;
use LCV\PlaylistBundle\Form\DataTransformer\FileToAudio;

class EditPlaylistType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              
          $builder
          ->add('title',     'text',array(
                 'label'  => "Titre de la playlist"))
          ->add('category', 'entity', array(
              'label'  => "Catégorie",
              'class'    => 'LCVPlaylistBundle:PlaylistCategory',
              'property' => 'name'))
          
         ->add('playlist_audios', 'collection', array(
                      'label' => ' ',
                      'type' => new PlaylistAudioType(),
                      'allow_add' => true,
                      'allow_delete' => true,
                      'by_reference' => false,
                      'required'      => false,
                      'options'=> array('label'=>" "),
                      'required' => false
                      ))

                      
          ->add('save',      'submit', array(
                'label'  => "Enregistrer",
                'attr'   =>  array ('class' => "calendar btn btn-default")))
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
        return 'lcv_playlistbundle_edit_playlist';
    }
    
    public function getParent() {
        return new PlaylistType();
    }
    
}
