<?php

namespace Trazeo\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Trazeo\BaseBundle\Entity\EGroupInvite;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Trazeo\BaseBundle\Entity\UserExtend;
use Trazeo\BaseBundle\Service\Helper;
use Trazeo\MyPageBundle\Entity\Page;

/**
 * @Route("/")
 */
class PublicController extends Controller
{
	/**
	 * @Route("/", name="home")
	 * @Template
	 */
    public function indexAction()
    {
        // Si estamos en una página personalizada redirigimos

        /** @var Helper $helper */
        $helper = $this->get('trazeo_base_helper');
        /** @var Page $page */
        $page = $helper->getPageBySubdomain();
        if ($page != null) {
            $response = $this->forward('TrazeoMyPageBundle:Front:landingPage', array(
                'subdomain'  => $page->getSubdomain()
            ));
            return $response;
        }

		$asset=$this->container->get('templating.helper.assets');

        // Si es una página normal, continuamos
    	$banners = array();
    	
    	$banners[0]['url'] = $asset->getUrl('cofinanciadores/banner/100/acerapeatonal.jpg');
    	$banners[0]['title'] = "Acera Peatonal";
    	$banners[0]['desc'] = "Entre todos hacemos calle.";
    	
    	$banners[1]['url'] = $asset->getUrl('cofinanciadores/banner/100/elportillo.jpg');
    	$banners[1]['title'] = "El Portillo";
    	$banners[1]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";

    	$banners[2]['url'] = $asset->getUrl('cofinanciadores/banner/100/quaip.png');;
    	$banners[2]['title'] = "QuaIP";
    	$banners[2]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";

    	$banners[3]['url'] = $asset->getUrl('cofinanciadores/banner/100/suspasitos.png');
    	$banners[3]['title'] = "SusPasitos";
    	$banners[3]['desc'] = "Pasito a pasito; por una ciudad limpia de coches y peligros para nuestros hijos.";    	

    	$banners[4]['url'] = $asset->getUrl('cofinanciadores/banner/250/Sopinet1.jpg');
    	$banners[4]['title'] = "Sopinet";
    	$banners[4]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";
    	 
    	$banners[5]['url'] = $asset->getUrl('cofinanciadores/banner/250/HelenDoron0.jpg');
    	$banners[5]['title'] = "Helen Doron";
   		$banners[5]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";
    	    	 
    	$banners[6]['url'] = $asset->getUrl('cofinanciadores/banner/100/suspasitos.png');
    	$banners[6]['title'] = "SusPasitos";
   		$banners[6]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";
    	    	 
   		$banners[7]['url'] = $asset->getUrl('cofinanciadores/banner/250/DCABO.png');
   		$banners[7]['title'] = "DCABO CONSULTORES";
   		$banners[7]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";

   		$banners[8]['url'] = $asset->getUrl('cofinanciadores/originales/banner/250/CentroCordoba.jpg');
   		$banners[8]['title'] = "Centro Córdoba";
   		$banners[8]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";
   		 
   		
   		/*
    	$banner[3]['url'] = "http://static.trazeo.es/banner/100/quaip.png";
    	$banner[3]['title'] = "DK Diseño kreativo";
    	$banner[3]['desc'] = "Imprescindible, oportuno y necesario Trazeo y sus caminos escolares seguros.";
    	*/
    	
    	
        return array(
        	'banners' => $banners
        	);
        //$this->render('TrazeoFrontBundle:Public:home.html.twig');
    }
    
    /**
     * @Route("/cofinanciadores", name="home_cofinanciadores"))
     * @Template
     */
    public function cofinanciadoresAction()
    {
    	return array();
    }
    
    /**
     * @Route("/invite/{email}/{token}/{id}", name="home_invite_user")
     * @Template
     */
    public function inviteAction($email, $token, $id) {
    	// Comprobar TOKEN
    	$em = $this->getDoctrine()->getManager();
    	$reGAI = $em->getRepository('TrazeoBaseBundle:EGroupAnonInvite');
    	$inviterow = $reGAI->findOneById($id);
    	
    	if ($inviterow->getToken() == $token
    			&& $inviterow->getEmail() == $email) {
    		// Mostramos la pantalla
    		return array(
    				'id' => $id,
    				'token' => $token,
    				'email' => $email
    		);
    	} else {
    		die("Error");
    	}
    }
    
    /**
     * @Route("/execInvite", name="home_execInvite_user")
     * @param Request $request
     */
    public function execInviteAction(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$pass1 = $request->get('_password1');
    	$pass2 = $request->get('_password2');
    	$id = $request->get('id');
    	$token = $request->get('token');    	
    	
    	if ($pass1 != $pass2) {
    		die("Pass no es el mismo");
    		// TODO: Comprobar pass no es el mismo
    	}
    	
    	$reGAI = $em->getRepository('TrazeoBaseBundle:EGroupAnonInvite');
    	$inviterow = $reGAI->findOneById($id);
    	
    	if ($inviterow->getToken() == $token) {
    		// Datos
    		$groupId = $inviterow->getGroup()->getId();
    		
    		//Creamos el usuario
    		$user = new \Application\Sonata\UserBundle\Entity\User;
    		$user->setEmail($inviterow->getEmail());
    		$user->setPlainPassword($pass1);
    		$user->setUsername($inviterow->getEmail());
    		$user->setEnabled(1);
    		
    		$em->persist($user);
    		$em->flush();		

    		// Creamos la invitación
    		$not = $this->container->get('sopinet_user_notification');
            $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($user,'panel_group');
    		$el = $not->addNotification(
    				'group.invite.user',
    				"TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
    				$inviterow->getUserCreated()->getId() . "," . $groupId,
    				$url,
                    $user,
                    null,
                    $this->generateUrl('panel_group')
    		);
            $el->setImportant(1);
            $em->persist($el);
            $em->flush();
               			
			$userextend = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($user);

    		$access = new EGroupInvite();
    		$access->setGroup($inviterow->getGroup());
    		$access->setUserextend($userextend);
            $access->setSender($inviterow->getUserCreated());
    		
    		$em->persist($access);
    		$em->flush();
    		
    		// Logueamos Usuario
    		$token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
    		$this->get("security.context")->setToken($token); //now the user is logged in
    		
    		// Lanzamos evento de Login
    		$request = $this->get("request");
    		$event = new InteractiveLoginEvent($request, $token);
    		$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);    		
    		
    		$container = $this->get('sopinet_flashMessages');
    		$notification = $container->addFlashMessages("success","¡Bienvenido! Acepta la invitación al Grupo y comienza a usar Trazeo");
    		//return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));
    		return $this->redirect($this->generateUrl('panel_dashboard')); 	
    	} else {
			die("Error");
    	}
    }
}
