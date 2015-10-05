<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class ArticleEditType extends ArticleType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->remove('date');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_articleedit';
    }
}
