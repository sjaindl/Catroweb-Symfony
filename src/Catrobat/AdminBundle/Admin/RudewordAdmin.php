<?php

namespace Catrobat\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class RudewordAdmin extends Admin
{

  // Fields to be shown on create/edit forms
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
    ->add('word', 'text', array('label' => 'Word'))
    ;
  }
  
  // Fields to be shown on filter forms
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
    ->add('word')
    ;
  }
  
  // Fields to be shown on lists
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
    ->addIdentifier('word')
    ;
  }

}
