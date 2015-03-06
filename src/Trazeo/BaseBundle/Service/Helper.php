<?php

namespace Trazeo\BaseBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Helper {
	private $_container;
				
	function __construct(ContainerInterface $container) {
		$this->_container = $container;
	}
	
	/**
	 * Get cities from city String
	 * 
	 * @param String $city
	 * @param number $limit
	 * @param Boolean $objects
	 * @return multitype:Array of City or Array of Strings
	 */
	function getCities($city, $limit = 10, $objects = false) {
		$em = $this->_container->get("doctrine.orm.entity_manager");

		$reJJ = $em->getRepository("JJsGeonamesBundle:City");
		
		$query = $reJJ->createQueryBuilder('a');
		
		if(!$objects)$param_return="a.id, a.nameUtf8";
		else $param_return="a";

		$query->select($param_return)
		->where('a.nameUtf8 LIKE :name')
		->setParameter('name', '%'.$city.'%')
		->setMaxResults($limit)
		->addOrderBy('a.id');
		
		$cities = $query->getQuery()->getArrayResult();

		// Función que compara los elementos segun su coincidencía
		function sorter($city) {
			
			return function ($a, $b) use ($city) {
				if(is_array($a)){
					similar_text($a['nameUtf8'],$city,$a_percent);
					similar_text($b['nameUtf8'],$city,$b_percent);
				}
				else{
					similar_text($a->getNameUtf8(),$city,$a_percent);
					similar_text($b->getNameUtf8(),$city,$b_percent);
				}
				if($a_percent==$b_percent){
					return 0;
				}
				else if($a_percent<$b_percent){
					return 1;
				}
				else{
					return -1;
				}
			};
		}
		usort($cities, sorter($city));
		
		//En caso de querer devolver un array de objetos lo construye 
		if ($objects) {
			$cities_old = $cities;
			$cities = array();
			foreach($cities_old as $c) {
				$cities[] = $reJJ->findOneById($c['id']);
			}
		}
		return $cities;		
	}
	/**
	 *
	 *
	 */
	function getAutoLoginUrl($user,$name,$args=null) {
		if($args!=null)$path=$this->_container->get('router')->generate($name, $args);
		else $path=$this->_container->get('router')->generate($name);
		$pos=strpos($path,'panel/');
		$path = substr($path, $pos);
		$path =$this->_container->get('urlhelper')->generateUrl($path,$user);
		$pos=strpos($path,'auto/');
		$path = substr($path, $pos);
		return $path;
	}

    function getPageBySubdomain($subdomain = null) {
        // TODO: DEBUG
        if ($_SERVER['HTTP_HOST'] == "localhost" && $subdomain == null) $subdomain = "torrelodones";
        if ($subdomain == null) {
            $parts=explode('.', $_SERVER["SERVER_NAME"]);
            $subdomain = $parts[0];
        }

        if ($subdomain == "beta" || $subdomain == "app") {
            return null;
        }

        $em = $this->_container->get("doctrine.orm.entity_manager");
        $repositoryPage = $em->getRepository("TrazeoMyPageBundle:Page");
        $page = $repositoryPage->findOneBySubdomain($subdomain);

        return $page;
    }

    function getDateTime($datetime_string) {
        $pos = strpos($datetime_string, "/");
        if ($pos !== false) {
            $temp_arr = explode("/", $datetime_string);
            $datetime_string_good = $temp_arr[2] . "-" . $temp_arr[1] . "-" . $temp_arr[0];
        } else {
            $datetime_string_good = $datetime_string;
        }
        return new \DateTime($datetime_string_good, new \DateTimeZone('Europe/Madrid'));
    }
}