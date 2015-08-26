<?php

namespace LCV\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LCV\CoreBundle\Entity\Notification;

class NotificationController extends Controller {
    
    public function deleteAction(Request $request) {
        $previousUrl = $request->headers->get('referer');
        $em=$this -> getDoctrine() -> getManager();
        $notifications=$em->getRepository('LCVCoreBundle:Notification')->findByUser($this->getUser());
        
        foreach ($notifications as $notification){
            $em->remove($notification);
        }
        $em->flush();
        return $this -> redirect($previousUrl);
    }
    
}
