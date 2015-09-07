<?php

namespace LCV\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;

use LCV\CommentBundle\Entity\CommentRepository;


class EditCommentType extends AbstractType
{
    private $comment;
    
    private $options = array();
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder
          ->add('content', 'textarea')
          ->add('save',      'submit', array(
                'attr'   =>  array ('class' => "btn btn-default")))
        ;
    }
    public function __construct(\LCV\CommentBundle\Entity\Comment $comment) {
        $this->comment=$comment; 
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\CommentBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->comment != null)
            return 'lcv_edit_commentbundle_article'.$this->comment->getId();
        return 'lcv_edit_commentbundle_article';
    }
    
    
}
