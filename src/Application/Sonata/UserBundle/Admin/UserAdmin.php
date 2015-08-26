<?php

namespace Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends SonataUserAdmin
{
    /**
        * {@inheritdoc}
        */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
                ->add('articles', 'entity', array('class' => 'LCV\PlatformBundle\Entity\Article'))
                ->add('comments', 'entity', array('class' => 'LCV\CommentBundle\Entity\Comment'))
                ->add('avatar', 'sonata_type_model_list', array(), array('link_parameters' => array('context' => 'news')));
        ;
    }
}