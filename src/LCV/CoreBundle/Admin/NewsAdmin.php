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
        ->add('abstract', 'text', array('label' => 'Résumé'))
		->add('article', 'entity', array('class' => 'LCV\PlatformBundle\Entity\Article'))
		;
	}
	
	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
		->add('type')
		->add('article')
		;
	}
	
	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
		->addIdentifier('type')
		->add('article')
        ->add('abstract');
		
	}
}