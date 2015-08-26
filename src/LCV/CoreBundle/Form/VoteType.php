<?php
namespace LCV\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class VoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value','integer',array('attr'
            => array('class'=>'rating_form',
                     'max' => '5',
                     'min' => '0',
                     'data-size'=>'xs',
                     'step'=> 1
            )))
             ->add('save', 'submit',array('attr'
                 => array('class'=>'btn-sm')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LCV\CoreBundle\Entity\Vote'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lcv_corebundle_vote';
    }
}
