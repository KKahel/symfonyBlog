<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Sdz\BlogBundle\Entity\Article;

use Sdz\BlogBundle\Form\ArticleType;
use Sdz\BlogBundle\Form\ArticleEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BlogController extends Controller
{

    public function indexAction($page)
    {
        // On récupère les derniers articles
        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->getArticles(3, $page); // 3 articles par page

        // Puis modifiez la ligne du render comme ceci, pour prendre en compte nos articles :
        return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page'       => $page,
            'nombrePage' => ceil(count($articles)/3)
        ));

    }

    public function articleAction($slug)
    {
        // On récupère l'article
        // nouvelle ligne de com
        $article = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->findArticleAvecCommentaireEtCompetenceBySlug($slug);

        $form = $this->createFormBuilder()->getForm();

        // Puis modifiez la ligne du render comme ceci, pour prendre encompte l'article :
        return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));

    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function ajouterAction(Request $request)
    {

        $article = new Article();
        // On crée le FormBuilder grâce à la méthode du contrôleur
        $form = $this->createForm(new ArticleType, $article);

        // On vérifie qu'elle est de type POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);
            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                return $this->redirect($this->generateUrl('sdzblog_voir',
                    array('slug' => $article->getSlug())));
            }
        }

        return $this->render('SdzBlogBundle:Blog:ajouter.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }

    public function modifierAction($slug,Request $request)
    {
        // Récupération d'un article déjà existant, d'id $id.
        $article = $this->getDoctrine()
            ->getRepository('SdzBlogBundle:Article')
            ->findArticleAvecCommentaireEtCompetenceBySlug($slug);

        $form = $this->createForm(new ArticleEditType, $article);

        if($request->isMethod('POST'))
        {
            //$this->get('session')->getFlashBag()->add('notice','Article bien enregistré');
            $form->handleRequest($request);
            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                return $this->redirect($this->generateUrl('sdzblog_voir',
                    array('slug' => $article->getSlug())));
            }
        }

        return $this->render('SdzBlogBundle:Blog:modifier.html.twig',
            array(
                'form' => $form->createView(),
                'article' => $article
            ));
    }

    public function supprimerAction($slug, Request $request)
    {
        //requete AJAX
        if($request->isXmlHttpRequest())
        {
            // On récupère l'article
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('SdzBlogBundle:Article')
                ->findArticleAvecCommentaireEtCompetenceBySlug($slug);

            if($article == null)
            {
                return new JsonResponse(array(
                    'success'=> true,
                    'msg'=>'Article inexistant'
                ));
            }

            $form = $this->createFormBuilder()->getForm();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->remove($article);
                // On déclenche la modification
                $em->flush();
                return new JsonResponse(array(
                    'success'=> true,
                    'msg'=>'L\'article a bien été supprimé'
                ));
            }
            else
            {
                return new JsonResponse(array(
                    'success'=> false,
                    'msg'=>'Formulaire invalide'
                ));
            }
        }
        return new JsonResponse(array(
            'success'=> false,
            'msg'=>''
        ));
    }

    public function menuAction($nb)
    {
        // On les articles
        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('SdzBlogBundle:Article')
            ->findAll();

        return $this->render('SdzBlogBundle:Blog:menu.html.twig', array('liste_articles' => $articles));
    }
}