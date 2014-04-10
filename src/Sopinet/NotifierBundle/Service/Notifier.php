<?php

namespace Sopinet\NotifierBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class Notifier{
	
	public function MessagesNotifier($type = 'success', $message = 'Sopinet Notifier Bundle'){
	
		$session = new Session();
		$session->getFlashBag()->add($type, $message);
	}
}