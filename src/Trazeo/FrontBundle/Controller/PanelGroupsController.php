<?php

namespace Trazeo\FrontBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trazeo\BaseBundle\Entity\EGroup;
use Trazeo\BaseBundle\Entity\EGroupRepository;
use Trazeo\BaseBundle\Entity\ERoute;
use Trazeo\BaseBundle\Entity\EGroupAccess;
use Trazeo\BaseBundle\Entity\EGroupInvite;
use Trazeo\BaseBundle\Entity\EChild;
use Trazeo\BaseBundle\Form\GroupType;
use Trazeo\BaseBundle\Controller\GroupsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Trazeo\BaseBundle\TrazeoBaseBundle;

/**
 * Groups controller.
 *
 * @Route("/panel/group")
 */
class PanelGroupsController extends Controller
{
    /**
     * User change visibility of a Group.
     *
     * @Route("/{id}/changevisibility/{visibility}", name="panel_group_changeVisibility")
     * @Method("GET")
     */
    public function changeVisibility(EGroup $id, $visibility){
        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($id);

        $userId = $user->getId();
        $groupAdmin = $group->getAdmin();
        $container = $this->get('sopinet_flashMessages');
        if($groupAdmin != $user ){
            $notification = $container->addFlashMessages("error","No tienes autorización para editar este grupo");
        }
        else{
            $group->setVisibility($visibility);
            $em->persist($group);
            $em->flush();
        }
        $request = $this->getRequest();

        return $this->redirect($this->generateUrl('panel'));
    }

    /**
     * User join Child to Group.
     *
     * @Route("/{group}/joinchild/{child}", name="panel_group_joinChild")
     * @Method("GET")
     */
    public function joinChildAction(EGroup $group, EChild $child) {

        $em = $this->getDoctrine()->getManager();

        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        $group->addChild($child);
        $em->persist($group);
        $em->flush();

        return $this->redirect($this->generateUrl('panel_group_timeline', array('id' => $group->getId())));
    }


    /**
     * User disjoin Child to Group.
     * @param EGroup $group
     * @param EChild $child
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{group}/removechild/{child}", name="panel_group_removeChild")
     * @Method("GET")
     */
    public function removeChildAction(EGroup $group, EChild $child)
    {

        $em = $this->getDoctrine()->getManager();

        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        $group->removeChild($child);
        $em->persist($group);
        $em->flush();

        return $this->redirect($this->generateUrl('panel_group_timeline', array('id' => $group->getId())));
    }

    /**
     * User join Group.
     * @param Integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/join/{id}", name="panel_group_join")
     * @Method("GET")
     * @Template()
     */
    public function joinGroupAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fosUser = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fosUser);
        $repositoryGroup = $em->getRepository('TrazeoBaseBundle:EGroup');
        $container = $this->get('sopinet_flashMessages');
        //Unimos al usuario al grupo
        try {
            $repositoryGroup->joinGroup($id, $user);
            $container->addFlashMessages("success", "Te has unido correctamente al grupo");
        } catch (Exception $e) {
            $container->addFlashMessages("warning", $e->getMessage());
        }

        return $this->redirect($this->generateUrl('panel_group'));
    }

    /**
     * User disjoin Group.
     * @param Integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/disjoin/{id}", name="panel_group_disjoin")
     * @Method("GET")
     * @Template()
     */
    public function disJoinGroupAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fosUser = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fosUser);
        $container = $this->get('sopinet_flashMessages');
        $reGroup = $em->getRepository('TrazeoBaseBundle:EGroup');
        //Sacamos al usuario del grupo
        try {
            $reGroup->disjoinGroup($id, $user);
            $container->addFlashMessages("warning", "Has salido del grupo");
        } catch (PreconditionFailedHttpException $e) {
            $container->addFlashMessages("warning", $e->getMessage());
        }

        return $this->redirect($this->generateUrl('panel_group'));
    }

    /**
     * Request to admin Group.
     *
     * @Route("/requestjoin/{id}", name="panel_group_requestJoin")
     * @Method("GET")
     * @Template()
     */
    public function requestJoinGroupAction($id) {

        $em = $this->getDoctrine()->getManager();
        // FOSUser y UserExtend correspondiente logueado
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        $userId = $user->getId();

        // Obtener grupo al que se quiere unir a través del param $id
        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($id);
        $groupId = $group->getId();

        $groupVisibility = $group->getVisibility();
        // Buscar si existe alguna petición con ese UserExtend y ese Group
        $requestUser = $em->getRepository('TrazeoBaseBundle:EGroupAccess')->findOneByUserextend($user);
        $requestGroup = $em->getRepository('TrazeoBaseBundle:EGroupAccess')->findOneByGroup($group);
        $container = $this->get('sopinet_flashMessages');
        if ($groupVisibility == 2 ){
            $notification = $container->addFlashMessages("warning","Sólo puedes unirte a un grupo oculto mediante invitación.");
            return $this->redirect($this->generateUrl('panel_group'));

        }
        // Comprobar que existen
        if($requestUser && $requestGroup == true){

            // Si existen, obtener el id de su registro en la base de datos
            $requestUserId = $requestUser->getId();
            $requestGroupId = $requestGroup->getId();
            // Comprobar que no tienen el mismo id de registro (petición duplicada)
            if($requestUserId = $requestGroupId) {
                // Excepción y redirección

                $notification = $container->addFlashMessages("warning","Ya has solicitado el acceso a este grupo anteriormente");
                return $this->redirect($this->generateUrl('panel_group'));

            }

        }else{
            // Si no existen los UserExtend y Group anteriormente obtenidos,
            // directamente se crea la petición
            $groupAdmin = $group->getAdmin();
            $groupAdminUser = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($groupAdmin);

            $fos_user_admin = $groupAdminUser->getUser();
            //ldd($fos_user_admin);
            $not = $this->container->get('sopinet_user_notification');
            $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($groupAdminUser->getUser(),'panel_group');
            $el = $not->addNotification(
                'group.join.request',
                "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
                $userId . "," . $groupId,
                $url,
                $groupAdminUser->getUser(),
                null,
                $this->generateUrl('panel_group')
            );
            $el->setImportant(1);
            $em->persist($el);
            $em->flush();

            $access = new EGroupAccess();
            $access->setGroup($group);
            $access->setUserextend($user);

            $em->persist($access);
            $em->flush();

            $notification = $container->addFlashMessages("success","Tu solicitud para unirte ha sido enviada al adminsitrador del grupo");
            return $this->redirect($this->generateUrl('panel_group'));

        }

    }


    /**
     * User adminGroup let an User to join with the request Group.
     *
     * @Route("/letjoin/{id}/{group}", name="panel_group_let_join")
     * @Method("GET")
     * @Template()
     */
    public function letJoinGroupAction($id, $group) {

        $em = $this->getDoctrine()->getManager();
        $container = $this->get('sopinet_flashMessages');
        $fos_user_current = $this->container->get('security.context')->getToken()->getUser();
        $user_current =$em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user_current);

        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($id);
        if (!$user) {
            $notification = $container->addFlashMessages("error","No puedes dar acceso porque el usuario ya no existe");
            return $this->redirect($this->generateUrl('panel_group'));
        }
        $fos_user = $user->getuser();
        $groupToJoin = $em->getRepository('TrazeoBaseBundle:EGroup')->find($group);

        if (!$groupToJoin) {
            $notification = $container->addFlashMessages("error","No puedes dar acceso porque el grupo ya no existe");
            return $this->redirect($this->generateUrl('panel_group'));
        }

        if(in_array($user, $groupToJoin->getUserextendgroups()->toArray())) {
            return $this->redirect($this->generateUrl('panel_group'));
        }

        $groupId = $groupToJoin->getId();
        $groupToJoin->addUserextendgroup($user);
        $em->persist($groupToJoin);
        $em->flush();

        //Children autojoin on parent join to group
        $childs=$user->getChilds();
        foreach($childs as $child){
            $groupToJoin->addChild($child);
        }
        $em->persist($groupToJoin);
        $em->flush();

        $userRequest = $em->getRepository('TrazeoBaseBundle:EGroupAccess')->findOneByUserextend($id);

        $em->remove($userRequest);
        $em->flush();

        $not = $this->container->get('sopinet_user_notification');
        $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($fos_user,'panel_group');
        $el = $not->addNotification(
            'group.join.let',
            "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
            $user_current->getId() . ',' . $groupId,
            $url,
            $fos_user,
            null,
            $this->generateUrl('panel_group')
        );
        $el->setImportant(1);
        $em->persist($el);
        $em->flush();

        $notification = $container->addFlashMessages("success","El usuario ha sido añadido al grupo");

        return $this->redirect($this->generateUrl('panel_group'));
    }

    /**
     * User adminGroup let an User to join with the request Group.
     *
     * @Route("/denyjoin/{id}/{group}", name="panel_group_deny_join")
     * @Method("GET")
     * @Template()
     */

    public function denyJoinGroupAction($id, $group) {

        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $fos_user_current = $this->container->get('security.context')->getToken()->getUser();
        $user_current =$em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user_current);

        $userRequest = $em->getRepository('TrazeoBaseBundle:EGroupAccess')->findOneByUserextend($id);
        $groupRequest = $em->getRepository('TrazeoBaseBundle:EGroup')->find($group);
        $group = $groupRequest->getId();

        $userextend = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($id);
        $fos_user= $userextend->getUser();
        $em->remove($userRequest);
        $em->flush();

        $groupAdmin = $groupRequest->getAdmin();
        $container = $this->get('sopinet_flashMessages');
        $notification = $container->addFlashMessages("success","Has rechazado la petición del usuario para unirse al grupo");

        $not = $this->container->get('sopinet_user_notification');
        $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($fos_user,'panel_group');
        $el = $not->addNotification(
            'group.join.deny',
            "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
            $user_current->getId() . ',' . $group,
            $url,
            $fos_user,
            null,
            $this->generateUrl('panel_group')
        );
        $el->setImportant(1);
        $em->persist($el);
        $em->flush();

        return $this->redirect($this->generateUrl('panel_group'));

    }


    /**
     * GroupAdmin invite an User to join a Group.
     *
     * @Route("/invite", name="panel_group_invite")
     * @Method("POST")
     * @Template()
     */
    public function inviteGroupAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');

        $container = $this->get('sopinet_flashMessages');

        $fos_user_current = $this->container->get('security.context')->getToken()->getUser();
        //$user_current = $um->findUserByEmail($fos_user_current);

        //ldd($fos_user_current);
        //ldd($user_current);

        $userEmail = $request->get('userEmail');
        $groupId = $request->get('group');

        $fos_user = $um->findUserByEmail($userEmail);
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($groupId);
        $groupUsers = $group->getUserextendgroups();

        foreach($groupUsers as $groupUser){
            if($user == $groupUser){

                $notification = $container->addFlashMessages("warning","El usuario ya forma parte del grupo");
                return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));
            }
        }


        if($fos_user != true){
            // Si el usuario no está registrado, habrá que registrarlo
            $reGAI = $em->getRepository('TrazeoBaseBundle:EGroupAnonInvite');
            $reGAI->createNew($group, $userEmail, $fos_user_current, $this);

            // $notification = $container->addFlashMessages("warning","El correo electrónico introducido no corresponde a ningún usuario");
            $notification = $container->addFlashMessages("success","Se ha enviado un email al usuario invitándolo al sistema Trazeo y a este grupo.");
            return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));
        }

        if($fos_user == $fos_user_current ){
            $notification = $container->addFlashMessages("warning","No necesitas invitación para unirte a un grupo del que eres administrador");
            return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));
        }

        // Obtener grupo al que se va a unir a través del param $id
        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($groupId);

        // Buscar si existe alguna petición con ese UserExtend y ese Group
        $requestUser = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findOneByUserextend($user);
        $requestGroup = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findOneByGroup($group);

        // Comprobar que existen
        if($requestUser && $requestGroup == true){

            // Si existen, obtener el id de su registro en la base de datos
            $requestUserId = $requestUser->getId();
            $requestGroupId = $requestGroup->getId();
            // Comprobar que no tienen el mismo id de registro (petición duplicada)
            if($requestUserId = $requestGroupId) {
                // Excepción y redirección
                $notification = $container->addFlashMessages("warning","Ya has invitado a este usuario anteriormente");
                return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));

            }

        }else{
            // Si no existen los UserExtend y Group anteriormente obtenidos,
            // directamente se crea la petición
            $user_current = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user_current->getId());
            $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($fos_user,'panel_group');
            $not = $this->container->get('sopinet_user_notification');
            $el = $not->addNotification(
                'group.invite.user',
                "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
                $user_current->getId() . "," . $groupId ,
                $url,
                $fos_user,
                null,
                $this->generateUrl('panel_group')
            );
            $el->setImportant(1);
            $em->persist($el);
            $em->flush();

            $access = new EGroupInvite();
            $access->setGroup($group);
            $access->setUserextend($user);
            $access->setSender($user_current);

            $em->persist($access);
            $em->flush();

            $container = $this->get('sopinet_flashMessages');
            $notification = $container->addFlashMessages("success","El usuario ha recibido tu invitación para unirse al grupo");
            return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));

        }

    }

    /**
     * User accept to join with a hidden group.
     *
     * @Route("/inviteaccept/{id}/{group}", name="panel_group_invite_accept")
     * @Method("GET")
     * @Template()
     */
    public function acceptInviteGroupAction($id, $group) {

        $em = $this->getDoctrine()->getManager();
        $fos_user_current = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($id);
        $groupToJoin = $em->getRepository('TrazeoBaseBundle:EGroup')->find($group);
        $container = $this->get('sopinet_flashMessages');

        if (!$groupToJoin) {
            $notification = $container->addFlashMessages("success","No puedes unirte al grupo porque ha sido eliminado");
            return $this->redirect($this->generateUrl('panel_group'));
        }

        if(in_array($user,$groupToJoin->getUserextendgroups()->toArray())){
            $notification = $container->addFlashMessages("success","Ya eres miembro de este grupo");
            return $this->redirect($this->generateUrl('panel_group'));
        }

        $groupToJoin->addUserextendgroup($user);
        $em->persist($groupToJoin);
        $em->flush();

        //Children autojoin on parent join to group
        $childs=$user->getChilds();
        foreach($childs as $child){
            $groupToJoin->addChild($child);
        }
        $em->persist($groupToJoin);
        $em->flush();

        $userRequest = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findOneByUserextend($id);
        $sender=$userRequest->getSender();
        $userSender = $em->getRepository('TrazeoBaseBundle:Userextend')->findOneById($sender);
        $fos_userSender = $userSender->getUser();
        $groupAdmin = $groupToJoin->getAdmin();
        $groupAdminUser = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($groupAdmin);

        $not = $this->container->get('sopinet_user_notification');
        $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($fos_userSender,'panel_group');
        $el = $not->addNotification(
            'group.invite.accept',
            "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
            // FIX: #4416 $groupAdminUser->getId() . "," . $group ,
            $user->getId() . "," . $group ,
            $url,
            $fos_userSender,
            null,
            $this->generateUrl('panel_group')
        );
        $el->setImportant(1);
        $em->persist($el);
        $em->flush();

        $em->remove($userRequest);
        $em->flush();

        $notification = $container->addFlashMessages("success","Te has unido correctamente al grupo");
        return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$group)));
    }


    /**
     * User adminGroup let an User to join with the request Group.
     *
     * @Route("/invitedeny/{id}/{group}", name="panel_group_invite_deny")
     * @Method("GET")
     * @Template()
     */

    public function denyInviteGroupAction($id,$group) {

        $em = $this->getDoctrine()->getManager();
        $fos_user_current = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($id);
        $userRequest = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findOneByUserextend($id);
        $groupEntity = $em->getRepository('TrazeoBaseBundle:EGroup')->find($group);
        $groupAdmin = $groupEntity->getAdmin();
        $groupAdminUser = $em->getRepository('TrazeoBaseBundle:UserExtend')->find($groupAdmin);
        $groupAdmin_fos_user = $groupAdminUser->getUser();
        $userRequest = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findOneByUserextend($id);
        $sender=$userRequest->getSender();
        $userSender = $em->getRepository('TrazeoBaseBundle:Userextend')->findOneById($sender);
        $fos_userSender = $userSender->getUser();

        $not = $this->container->get('sopinet_user_notification');
        $url=$this->get('trazeo_base_helper')->getAutoLoginUrl($fos_userSender,'panel_group');
        $el = $not->addNotification(
            'group.invite.deny',
            "TrazeoBaseBundle:UserExtend,TrazeoBaseBundle:EGroup",
            $user->getId() . "," . $group ,
            $url,
            $fos_userSender,
            null,
            $this->generateUrl('panel_group')
        );
        $el->setImportant(1);
        $em->persist($el);
        $em->flush();

        $em->remove($userRequest);
        $em->flush();
        $container = $this->get('sopinet_flashMessages');
        $notification = $container->addFlashMessages("success","Has rechazado la invitación");

        return $this->redirect($this->generateUrl('panel_group'));

    }

    /**
     * Lists all Groups entities.
     *
     * @Route("/", name="panel_group")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
        $userId = $user->getId();

        $groupsMember = $user->getGroups();
        $allGroups = $em->getRepository('TrazeoBaseBundle:EGroup')->findAll();
        $restGroups = array_diff($allGroups,$groupsMember->toArray());

        // City filter
        $city = null; // request filter
        if ($user->getCity() != null) $city = $user->getCity()->getId();
        if ($city == null) $city = "all";
        $cities = array();
        $iscity = false;
        foreach($restGroups as $group) {
            if ($group->getCity() != null) {
                if ($city == $group->getCity()->getId()) $iscity = true;
                $cities[$group->getCity()->getId()] = $group->getCity();
            }
        }
        if (!$iscity) $city = "all"; // if no cities for user
        // End City Filter
        $groupsAdmin = $user->getAdminGroups();
        $userAdmin = $em->getRepository('TrazeoBaseBundle:EGroup')->findByAdmin($userId);

        $allGroupsAccess = $em->getRepository('TrazeoBaseBundle:EGroupAccess')->findAll();
        $allGroupsInvite = $em->getRepository('TrazeoBaseBundle:EGroupInvite')->findAll();

        return array(
            'user' => $user,
            'userAdmin' => $userAdmin,
            'groupsAdmin' => $groupsAdmin,
            'allGroupsAccess' => $allGroupsAccess,
            'allGroupsInvite' => $allGroupsInvite,
            'restGroups' => $restGroups,
            'groupsMember' => $groupsMember,
            'cities' => $cities,
            'city' => $city

        );
    }

    /**
     * Creates a new Group entity.
     *
     * @Route("/", name="panel_group_create")
     * @Method("POST")
     * @Template("TrazeoFrontBundle:PanelGroups:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $group = new EGroup();
        $form = $this->createCreateForm($group);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $groupForm =$form->getData();
        $groupName = $groupForm->getName();

        $groupUnique = $em->getRepository('TrazeoBaseBundle:EGroup')->findOneByName($groupName);

        if($groupUnique == true){

            $groupUniqueName = $groupUnique->getName();

            if($groupUniqueName == $groupName){

                $container = $this->get('sopinet_flashMessages');
                $container->addFlashMessages("warning", "Ya existe un grupo con el nombre que has indicado, ");
                return $this->redirect($this->generateUrl('panel_group_new'));
            }
        }



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //Obtener usuarios que tengan marcada la opcion de conexion con civiclub
            $reUserValue = $em->getRepository("SopinetUserPreferencesBundle:UserValue");
            $civiclub_setting = $em->getRepository("SopinetUserPreferencesBundle:UserSetting")->findOneByName("civiclub_conexion");

            $city = $request->get('city');
            $helper = $this->get('trazeo_base_helper');
            $city_entity = $helper->getCities($city, 10, true);
            if (count($city_entity) > 0) {
                $group->setCity($city_entity[0]);
            }
            $fos_user = $this->container->get('security.context')->getToken()->getUser();
            $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
            $group->setAdmin($user);
            $group->addUserextendgroup($user);
            $now=$group->getHasRide();
            $group->setHasRide(0);
            $em->persist($group);
            $em->flush();

            $formData = $form->getData();
            $groupId = $formData->getId();

            //Children autojoin on parent create group
            $childs=$user->getChilds();
            foreach($childs as $child){
                $group->addChild($child);
            }
            $em->persist($group);
            $em->flush();
            $sopinetuserextend=$em->getRepository("SopinetUserBundle:SopinetUserExtend")->findOneByUser($user->getUser());
            $container = $this->get('sopinet_gamification');
            $container->addUserAction(
                "Create_Group",
                "TrazeoBaseBundle:UserExtend",
                $user->getId(),
                $user,
                1,
                false
            );
            if(!$now)return $this->redirect($this->generateUrl('panel_group_timeline',array('id'=>$groupId)));
            else return $this->redirect($this->generateUrl('panel_route_new',array('id'=>$group->getId())));
        }

        return array(
            'entity' => $group,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Group entity.
     *
     * @param Group $group
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EGroup $group)
    {
        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
        if($user->getCountry()!=null){
            $cityCode = $em->getRepository('JJs\Bundle\GeonamesBundle\Entity\Country')->find($user->getCountry()->getId());
            $cityCodeId = $cityCode->getId();
        }
        else $cityCodeId =1;
        /** @var EGroupRepository $reSchool */
        $reSchool = $em->getRepository('TrazeoBaseBundle:EGroup');
        $schools=$reSchool->createQueryBuilder('u')->select('u.school1')->distinct()->getQuery()->getScalarResult();
        $schools = array_map('current', $schools);

        $form = $this->createForm(new GroupType(), $group, array(
            'action' => $this->generateUrl('panel_group_create'),
            'method' => 'POST',
            'attr' => array(
                'Groups.help.name' => $this->get('translator')->trans('Groups.help.name'),
                'Groups.help.name2' => $this->get('translator')->trans('Groups.help.name2'),
                'Groups.help.route' => $this->get('translator')->trans('Groups.help.route'),
                'Groups.help.city' => $this->get('translator')->trans('Groups.help.city'),
                'Groups.help.school' => $this->get('translator')->trans('Groups.help.shool'),
                'default' => $cityCodeId,
            ),
            'schools'=>$schools
        ));
        $form->add('hasRide', 'hidden', array('data' => 0));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Groups entity.
     *
     * @Route("/new", name="panel_group_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $group = new EGroup();
        $form   = $this->createCreateForm($group);

        return array(
            'entity' => $group,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Groups entity.
     *
     * @Route("/{id}", name="panel_groups_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TrazeoBaseBundle:EGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Groups entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'group'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Group entity.
     *
     * @Route("/{id}/edit", name="panel_group_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($id);

        $userId = $user->getId();
        $groupAdmin = $group->getAdmin();
        $container = $this->get('sopinet_flashMessages');
        if($groupAdmin != $user ){

            $notification = $container->addFlashMessages("error","No tienes autorización para editar este grupo");
            return $this->redirect($this->generateUrl('panel_dashboard'));
        }
        if (!$group) {

            $notification = $container->addFlashMessages("warning","No existe el grupo o ha sido eliminado");
            return $this->redirect($this->generateUrl('panel_dashboard'));
        }

        $editForm = $this->createEditForm($group);
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'group'      => $group,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Group entity.
     *
     * @param EGroup $group
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(EGroup $group)
    {
        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);
        if($user->getCountry()!=null){
            $cityCode = $em->getRepository('JJs\Bundle\GeonamesBundle\Entity\Country')->find($user->getCountry()->getId());
            $cityCodeId = $cityCode->getId();
        }
        else $cityCodeId =1;

        /** @var EGroupRepository $reSchool */
        $reSchool = $em->getRepository('TrazeoBaseBundle:EGroup');
        $schools=$reSchool->createQueryBuilder('u')->select('u.school1')->distinct()->getQuery()->getScalarResult();
        $schools = array_map('current', $schools);

        $form = $this->createForm(new GroupType(), $group, array(
            'action' => $this->generateUrl('panel_group_update',array('id' => $group->getId())),
            'method' => 'POST',
            'attr' => array(
                'Groups.help.name' => $this->get('translator')->trans('Groups.help.name'),
                'Groups.help.name2' => $this->get('translator')->trans('Groups.help.name2'),
                'Groups.help.route' => $this->get('translator')->trans('Groups.help.route'),
                'Groups.help.city' => $this->get('translator')->trans('Groups.help.city'),
                'Groups.help.school' => $this->get('translator')->trans('Groups.help.shool'),
                'default' => $cityCodeId,
            ),
            'schools'=>$schools
        ));
        //$form->add('city',null,array('data'=>$cityCodeId, 'attr'=>array('style'=>'display:none;')));       
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * @Route("/{id}/timeline", name="panel_group_timeline")
     * @Template()
     */
    public function timelineAction(Egroup $group)
    {
        $em = $this->getDoctrine()->getManager();
        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        // Comprobación de que el padre pertenece a ese grupo, y, por tanto
        // puede entrar en esta pantalla
        // Si no tiene acceso lo mandamos al listado de Grupos
        $can = false;
        foreach($group->getUserextendgroups()->toArray() as $checkuser) {
            if ($checkuser->getId() == $user->getId()) $can = true;
        }
        if (!$can) {
            $container = $this->get('sopinet_flashMessages');
            $notification = $container->addFlashMessages("error","No tiene acceso a este grupo, por favor, solicítelo antes de poder participar en el Muro");
            return $this->redirect($this->generateUrl('panel_group'));
        }

        //Listado de niños que están en el grupo y pertenecen al usuario logueado
        $userchilds = $user->getChilds()->toArray();
        $groupchilds = $group->getChilds()->toArray();
        $childs = array_intersect($userchilds, $groupchilds);
        $routes = $em->getRepository('TrazeoBaseBundle:ERoute')->findAll();

        //Listado de niños que no están en el grupo y pertenecen al padre
        $childsNoGroup = array_diff($userchilds, $childs);

        return array(
            'childsNoGroup' => $childsNoGroup,
            'childs' => $childs,
            'user' => $user,
            'group' => $group,
            'routes' => $routes
        );

    }


    /**
     * Let AdminGroup to change Group Route.
     *
     * @Route("/setroute/{group}/{route}", name="panel_group_setRoute")
     * @Template()
     */

    public function setRouteAction($group,$route) {

        $em = $this->getDoctrine()->getManager();

        $groupEntity = $em->getRepository('TrazeoBaseBundle:EGroup')->findOneById($group);
        $routeEntity = $em->getRepository('TrazeoBaseBundle:ERoute')->find($route);

        $groupEntity->setRoute($routeEntity);
        $em->persist($groupEntity);
        $em->flush();
        $container = $this->get('sopinet_flashMessages');
        $notification = $container->addFlashMessages("success","La ruta ha sido asignada a este grupo");
        return $this->redirect($this->generateUrl('panel_group_timeline', array('id' => $group)));
    }



    /**
     * Edits an existing Groups entity.
     *
     * @Route("/{id}", name="panel_group_update")
     * @Method("POST")
     * @Template("TrazeoFrontBundle:PanelGroups:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $group = $em->getRepository('TrazeoBaseBundle:EGroup')->find($id);

        if (!$group) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($group);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $city = $request->get('city');
            $helper = $this->get('trazeo_base_helper');
            $city_entity = $helper->getCities($city, 10, true);
            if (count($city_entity) > 0) {
                $group->setCity($city_entity[0]);
            }

            $em->persist($group);
            $em->flush();

            return $this->redirect($this->generateUrl('panel_group_edit', array('id' => $id)));
        }

        return array(
            'group'      => $group,
            'edit_form'   => $editForm->createView()
            //'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Groups entity.
     *
     * @Route("/{id}/delete", name="panel_group_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $fos_user = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('TrazeoBaseBundle:UserExtend')->findOneByUser($fos_user);

        /** @var \Trazeo\BaseBundle\Entity\EGroupRepository $repositoryGroup */
        $repositoryGroup=$em->getRepository('TrazeoBaseBundle:EGroup');

        $container = $this->get('sopinet_flashMessages');

        //Tratamos de borrar el grupo
        try{
            $repositoryGroup->userDeleteGroup($id,$user);
            $notification = $container->addFlashMessages("success","El grupo ha sido eliminado");
            return $this->redirect($this->generateUrl('panel_dashboard'));
        }
        catch(PreconditionFailedHttpException $e){
            $notification = $container->addFlashMessages("warning","El grupo que intentas eliminar no existe");
            return $this->redirect($this->generateUrl('panel_dashboard'));
        }
        catch(AccessDeniedException $e){
            $notification = $container->addFlashMessages("error","Sólo el administrador puede eliminar un grupo");
            return $this->redirect($this->generateUrl('panel_dashboard'));
        }
    }

    /**
     * Creates a form to delete a Groups entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panel_group_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}