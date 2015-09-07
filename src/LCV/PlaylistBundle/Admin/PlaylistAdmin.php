<?php
// src/LCV/PlatformBundle/Admin/CategoryAdmin.php

namespace LCV\PlaylistBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use LCV\PlaylistBundle\Entity\AudioFile;
use LCV\PlaylistBundle\Form\AudioFileType;

class PlaylistAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('title', 'text', array('label' => 'titre'))
        ->add('audios', 'collection', array(
                      'label' => ' ',
                      'type' => new AudioFileType(),
                      'required'      => false,
                      'options'=> array('label'=>" ")))
        ;
    }
    
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('title')
        ->add('date')
        ;
    }
    
    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('title')
        ->add('playlist')
        ->add('audios', 'collection', array(
                      'label' => ' ',
                      'type' => new AudioFileType(),
                      'required'      => false,
                      'options'=> array('label'=>" ")))
        ;
    }
}