<?php
namespace LCV\PlaylistBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use LCV\PlaylistBundle\Entity\AudioFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadListener
{
    protected $em;
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $category_id = $event->getRequest()->get('category');
        $category=$this->em->getRepository('LCVPlaylistBundle:AudioCategory') -> findOneById($category_id);
        
        $Upfile=$event->getRequest()->files->get('files')[0];
        $name=$event->getFile()->getFileName();
        $file = new UploadedFile(__DIR__ . '/../../../../web/uploads/audio/'.$name,$name);
        
        //Création de l'objet audio File
        $audio=new AudioFile();
        $audio->setFile($file);
        $audio->setTitle($Upfile->getClientOriginalName());
        $audio->setName($name);
        $audio->defineExtension();
        $audio->setPath(__DIR__ . '/../../../../web/uploads/audio/'.$audio->getName());
        $audio->setCategory($category);
        $em=$this->em;
        
        $em->persist($audio);
        $em->flush();
        
        
        //Création de la réponse
        $response = $event->getResponse();
        $response['files'] = array('name' => $audio->getTitle());
    }
}