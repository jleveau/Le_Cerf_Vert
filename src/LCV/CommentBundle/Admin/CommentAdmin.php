<?php
// src/LCV/PlatformBundle/Admin/ArticleAdmin.php

namespace LCV\CommentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('id', 'text', array('label' => 'ID'))
        ->add('article', 'entity', array('class' => 'LCV\PlatformBundle\Entity\Article'))
        ->add('date')
        ->add('updatedAt')
        ->add('content', 'ckeditor', array(
            'config' => array(
                'contentsLangDirection' => 'fr',
                'scayt_autoStartup' => true,
                'scayt_sLang' => 'fr_FR',
                'toolbar' => array(
                    array(
                        'name'  => 'document',
                        'items' => array('Source', '-', 'NewPage', 'DocProps'),
                      ),
                    array(
                        'name' => 'insert',
                        'items' => array('Image'),
                    ),
                    array(
                        'name' => 'editing',
                        'items' => array('Scayt'),
                    ),
                    array(
                          'name' => 'basicstyles',
                         'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                    ),
                )     
             ),
          )) //if no type is specified, SonataAdminBundle tries to guess it
        ->add('author')
        ->add('article')
        ;
    }
    
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('date')
        ->add('article')
        ->add('updatedAt')
        ;
    }
    
    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('id')
        ->addIdentifier('article')
        ->add('date')
        ->add('updatedAt')
         ->add('content')
         ->add('author')
        ;
    }
}