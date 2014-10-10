<?php

namespace Catrobat\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class FeaturedProgramsAdmin extends Admin
{

  // Fields to be shown on create/edit forms
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
    ->add('image', 'text', array('label' => 'Image'))
    ->add('url', 'text', array('label' => 'URL'))
    ->add('program', 'entity', array('class' => 'Catrobat\CoreBundle\Entity\Program'), array('admin_code' => 'catrowebadmin.block.all_programs'))
    ;
  }
  
  // Fields to be shown on filter forms
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
  //  ->add('program')
    ;
  }
  
  // Fields to be shown on lists
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
    ->addIdentifier('program', null, array('admin_code' => 'catrowebadmin.block.all_programs'))
    ->addIdentifier('image')
    ->addIdentifier('url')
    ->add('_action', 'actions', array(
        'actions' => array(
            'edit' => array(),
        )
    ))
    ;
  }

}
