<?php

namespace Trazeo\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/panel/children")
 */
class PanelChildrenController extends Controller
{
	/**
	 * @Route("/new", name="panel_children_new"))
	 * @Template
	 */
    public function newChildrenAction()
    {
        return $this->render('TrazeoFrontBundle:PanelChildren:new.html.twig');
    }
    
    /**
     * @Route("/edit", name="panel_children_edit"))
     * @Template
     */
    public function editChildAction()
    {
    	return $this->render('TrazeoFrontBundle:PanelChildren:edit.html.twig');
    }
    
}
