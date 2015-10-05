<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array('class' => 'date'),
            ))
            ->add('titre', 'text')
            ->add('contenu', 'textarea')
            ->add('publication','checkbox',array('required'=>false))
            ->add('auteur', 'text')
            ->add('image', new ImageType())
            ->add('categories', 'entity', array(
                    'class'    => 'SdzBlogBundle:Categorie',
                    'choice_label' => 'nom',
                    'multiple' => true,
                    'expanded' => false)
            );

//        $factory = $builder->getFormFactory();
//
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function(FormEvent $event) use ($factory) {
//                $article = $event->getData();
//                if(null === $article){
//                    return;
//                }
//                if(true === $article->getPublication()){
//                    $event->getForm()->remove('publication');
//                }
//            });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_article';
    }
}
