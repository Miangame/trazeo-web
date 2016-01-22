<?php

namespace Trazeo\MyPageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserDirectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'Register.email',
                    'data-content' => 'Register.email'
                ),'required' => true))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Ambas contraseñas deben coincidir',
                'required' => true,
                'options' => array('attr' => array('class' => 'password-field')),
                'first_options' => array('attr' => array('placeholder' => 'Form.profile.pass',        					'data-toggle' => 'popover',
                    'data-placement' => 'right',
                    'data-content' => 'Form.profile.pass')),
                'second_options' => array ('attr' => array('placeholder' => 'Form.profile.repeatpass',        					'data-toggle' => 'popover',
                    'data-placement' => 'right',
                    'data-content' => 'Form.profile.repeatpass')),

            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'trazeo_mypagebundle_userdirecttype';
    }
}