<?php

namespace Trazeo\BaseBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ECatalogItemAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('points', null, array('label' => 'list.label_points'))
            ->add('company', null, array('label' => 'list.label_company'))
            ->add('title', null, array('label' => 'list.label_title'))
            ->add('description', null, array('label' => 'list.label_description'))
            ->add('link', null, array('label' => 'list.label_link'))
            ->add('image', null, array('label' => 'list.label_file'))
            ->add('createdAt', null, array('label' => 'show.label_created_at'))
            ->add('updatedAt', null, array('label' => 'show.label_updated_at'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('points', null, array('label' => 'list.label_points'))
            ->add('company', null, array('label' => 'list.label_company'))
            ->add('title', null, array('label' => 'list.label_title'))
            ->add('description', null, array('label' => 'list.label_description'))
            ->add('link', null, array('label' => 'list.label_link'))
            ->add('image', null, array('label' => 'list.label_file'))
            ->add('createdAt', null, array('label' => 'show.label_created_at'))
            ->add('updatedAt', null, array('label' => 'show.label_updated_at'))
            ->add('_action', 'actions', array(
                'label' => 'list.label_action',
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('points', null, array('label' => 'list.label_points'))
            ->add('company', null, array('label' => 'list.label_company'))
            ->add('title', null, array('label' => 'list.label_title'))
            ->add('description', null, array('label' => 'list.label_description'))
            ->add('link', null, array('label' => 'list.label_link'))
            ->add('imageFile', 'vich_image', array(
                'label' => 'list.label_file',
                'required' => false,
                'allow_delete' => true
            ))
            ->add('createdAt', null, array('label' => 'show.label_created_at'))
            ->add('updatedAt', null, array('label' => 'show.label_updated_at'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('points', null, array('label' => 'list.label_points'))
            ->add('company', null, array('label' => 'list.label_company'))
            ->add('title', null, array('label' => 'list.label_title'))
            ->add('description', null, array('label' => 'list.label_description'))
            ->add('link', null, array('label' => 'list.label_link'))
            ->add('image', null, array('label' => 'list.label_file'))
            ->add('createdAt', null, array('label' => 'show.label_created_at'))
            ->add('updatedAt', null, array('label' => 'show.label_updated_at'))
        ;
    }
}
