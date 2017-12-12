<?php

namespace Damien\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('::base.html.twig');
    }
    
    public function loginAction(Request $request)
    {
        
        $authUtils = $this->get('security.authentication_utils');
        
        //get the login error if one
        $error = $authUtils->getLastAuthenticationError();
       
        //last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('::admin/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
    
//    public function listactusAction()ARTILCE
//    {
//        return $this->render('::blog/listactus.html.twig');
//    }
//    
//    public function addactuAction()
//    {
//        return $this->render('::blog/addactu.html.twig');
//    }
//    
//    public function listmediaAction()
//    {
//        return $this->render('::blog/listmedia.html.twig');
//    }
//    
//    public function addmediaAction()
//    {
//        return $this->render('::blog/addmedia.html.twig');
//    }
}
