<?php
// src/LCV/PlatformBundle/Admin/ArticleAdmin.php

namespace LCV\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewsAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('title', 'text', array('label' => 'Titre de l\'article'))
		->add('author', 'entity', array('class' => 'Application\Sonata\UserBundle\Entity\User'))
          ->add('content', 'textarea')
          ->add('date')
          		
		;
	}
	
	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
		->add('title')
		->add('author')
		->add('date')
		;
	}
	
	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
		->addIdentifier('title')
		->add('author')
		->add('date')
		;
	}
}