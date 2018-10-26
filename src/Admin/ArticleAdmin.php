<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('author')
            ->add('category')
            ->add('title', TextType::class)
            ->add('content')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('category')
            ->add('author')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title', null, ['editable' => true])
            ->add('category')
            ->add('author')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => [],
                    'delete' => []
                )
            ))
        ;
    }

    public function preUpdate($object)
    {
        $this->getConfigurationPool()->getContainer()->get('e');
        parent::preUpdate($object); // TODO: Change the autogenerated stub
    }
}