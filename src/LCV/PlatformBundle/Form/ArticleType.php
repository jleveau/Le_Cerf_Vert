<?php

namespace LCV\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use LCV\PlatformBundle\Entity\ArticleRepository;


class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              
          $builder
          ->add('title',     'text')
          ->add('content', 'ckeditor', array(

            'config' => array(
            	'contentsLangDirection' => 'fr',
            	'scayt_autoStartup' => true,
            	'scayt_sLang' => 'fr_FR',
            	'extraPlugins' => 'colorbutton',
        	    'toolbar' => array(

                    array(
               			'name'  => 'document',
              			'items' => array('Source', '-', 'NewPage', 'DocProps'),
          			  ),
        	    	array(
        	    			'name' => 'styles',
        	    			'items' => array('Styles','Format'),
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
        	    	array(
        	    			'name' => 'paragraph',
        	    			'items' => array('NumberedList','BulletedList','-','Outdent,Indent','-','Blockquote'),
        	    	),

                )     
             ),
          ))
          ->add('save',      'submit', array(
                'attr'   =>  array ('class' => "btn btn-default")))
          ->add('category', 'entity', array(
              'class'    => 'LCVPlatformBundle:Category',
              'property' => 'name'))
        ;

        
       
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\PlatformBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_platformbundle_article';
    }
    
    
}
