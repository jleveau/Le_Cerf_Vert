<?php
// src/LCV/PlatformBundle/Admin/ArticleAdmin.php

namespace LCV\PlatformBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('title', 'text', array('label' => 'Titre de l\'article'))
		->add('author', 'entity', array('class' => 'Application\Sonata\UserBundle\Entity\User'))
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
             )))
          ->add('rate')
          ->add('view')
           //if no type is specified, SonataAdminBundle tries to guess it
          ->add('category', 'entity', array(
          		'class'    => 'LCVPlatformBundle:Category',
          		'property' => 'name'))
          		
		;
	}
	
	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
		->add('title')
		->add('author')
		;
	}
	
	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
		->addIdentifier('title')
		->add('author')
		->add('category')
		->add('date')
		->add('updatedAt')
		;
	}
}