<?php

namespace Damien\BlogBundle\Controller;

use Damien\BlogBundle\Entity\Category;
use Damien\BlogBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    
    public function listAction($id)
    {
        
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('DamienBlogBundle:Category')
        ;
        
        $listCategories = $repository->myFindAll();
        
        return $this->render('::admin/category/list.html.twig', array(
            'listCategories' => $listCategories,
        ));
    }
    
    public function addAction(Request $request)
    {
        
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'catégorie ajouté !');

            return $this->redirectToRoute('admin_list_category');
        }
        
        return $this->render('::admin/category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function editAction(Request $request, $id)
    {
        
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('DamienBlogBundle:Category')
        ;

        $category = $repository->find($id);
        
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'catégorie ajouté !');

            return $this->redirectToRoute('admin_edit_category', array(
                'id' => $id
            ));
        }
                
        return $this->render('::admin/category/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function trashedAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('DamienBlogBundle:Category');

        $category = $repository->find($id);
        $category->setIsTrashed(true);
            
        $em->persist($category);
        $em->flush();
        
        return $this->redirectToRoute('admin_list_category');
    }
    
//    public function deleteAction()
//    {
//        
//        $repository = $this
//                ->getDoctrine()
//                ->getManager()
//                ->getRepository('DamienBlogBundle:Category')
//        ;
//        
//        $listCategories = $repository->myFindAll();
//        
//        return $this->render('::admin/category/delete.html.twig', array(
//            'listCategories' => $listCategories,
//        ));
//    }
    
}
