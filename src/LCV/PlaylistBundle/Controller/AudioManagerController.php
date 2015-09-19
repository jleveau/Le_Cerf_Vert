<?php

namespace LCV\PlaylistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use LCV\PlaylistBundle\Entity\AudioManager;
use LCV\PlaylistBundle\Form\AudioFileManagerUploadType;
use LCV\PlaylistBundle\Entity\AudioFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use LCV\PlaylistBundle\Entity\AudioCategory;
use LCV\PlaylistBundle\Form\AudioCategoryType;
use LCV\PlaylistBundle\Entity\PlaylistAudio;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use ZipArchive;

class AudioManagerController extends Controller
{
    public function addAction($category_id,Request $request )
    {
        $em = $this -> getDoctrine() -> getManager();
        $category=$em->getRepository('LCVPlaylistBundle:AudioCategory') -> findOneById($category_id);
        return $this -> render('LCVPlaylistBundle:Audio:add.html.twig', array('category' => $category));
    }

    public function deleteAction(Request $request)
    {
        return $this->render('LCVPlaylistBundle:Audio:delete.html.twig', array(
                // ...
            ));    }

    public function viewAction(Request $request)
    {
        return $this->render('LCVPlaylistBundle:Audio:view.html.twig', array(
                // ...
            ));    
    }
    
    public function indexAction(Request $request)
    {
        $controller = $this;
        $em = $this -> getDoctrine() -> getManager();
        $repo = $em->getRepository('LCVPlaylistBundle:AudioCategory');
        $query = $em
            ->createQueryBuilder()
            ->select('node')
            ->from('LCV\PlaylistBundle\Entity\AudioCategory', 'node')
            ->leftJoin('node.audios','audio')
            ->addSelect('audio')
            ->orderBy('node.root, node.lft', 'ASC')
            ->getQuery()
        ;
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul><input name=node class="root" type="checkbox" value="" >',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => function($node){
                $audios_html='
                <span id="cat_'.$node['id'].'" class="category_name"><strong>'.$node['name'].'</strong></span>
                
                <div class="btn-group " role="group"> 
                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        
                         <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <li ><a class="add_audio_link" href='.$this->generateUrl("lcv_platlist_audio_add",array("category_id"=>$node['id'])).'>Ajouter des morceaux</a></li>
                      <li class="add_cat_link" id='.$node['id'].' data-toggle="modal" data-target="#modal_form_category">Ajouter une sous catégorie</li>
                    </ul>
                </div>
                <ul>';
                foreach ($node['audios'] as $audio){
                    $audios_html=$audios_html.
                        '<li id="'.$audio["title"].'.'.$audio["id"].'" class="leaf" data='.$this->container->getParameter('audioRootDir').$audio['name'].'>
                              <input name="audio" type="checkbox" value="" >
                              <button  type="button" class="audio_play_button btn btn-default btn-xs glyphicon glyphicon-play"></button>
                              '.$audio['title'].'
                         </li> ' ;
                }
                $audios_html=$audios_html.'</ul>';
                        
                return $audios_html;
                
            }
        );
        $tree = $repo->buildTree($query->getArrayResult(), $options);
                
        
        $form_category = $this->createFormBuilder()
             ->add('name','text',array(
            'label' => 'Nom :',
            'required'=> true,
            ))
            ->add('parent','integer',array(
                'attr' => array("class" => "hidden"),
                'label'=>" "
            ))
            ->add('save','submit',array(
                    'label' => 'Enregistrer'
             ))
            ->getForm();
        $playlist_repo=$em->getRepository('LCVPlaylistBundle:Playlist');
        $user =$this->get('security.context')->getToken()->getUser();
        $form_playlist = $this->createFormBuilder()
            ->add('title', 'entity', array(
                'class' => 'LCVPlaylistBundle:Playlist',
                'property' => 'title',
                'label' => 'Titre',
                'query_builder' => function(\LCV\PlaylistBundle\Entity\PlaylistRepository $playlist_repo) use ($user)
                {
                    return $playlist_repo->createQueryBuilder('playlist')
                    ->where('playlist.author = :user')
                    ->setParameter('user', $user->getId());
                     
                },
            ))
            ->add('save','submit',array(
                    'label' => 'Enregistrer',
                    ))
            ->getForm();

        return $this->render('LCVPlaylistBundle:Audio:view.html.twig', array(
                'form_category' => $form_category-> createView(),
                'form_playlist' => $form_playlist-> createView(),
                'tree' => $tree
            ));
            
    }

    public function addAudioToPlaylistAction(Request $request){
        if($request->isXmlHttpRequest())
        {
            $data = $request->request;
            $audios = $data->get('audios');
            $playlist_title = $request->request->get('playlist');
    
            $em = $this->container->get('doctrine')->getEntityManager();
            $audio_repo = $repo = $em->getRepository('LCVPlaylistBundle:AudioFile');
            $playlist_audio_repo = $repo = $em->getRepository('LCVPlaylistBundle:PlaylistAudio');
            $playlist_repo = $em->getRepository('LCVPlaylistBundle:Playlist');
            
            $playlist= $playlist_repo->findOneByTitle($playlist_title);
            
            $success=array();
            $errors=array();
            foreach ($audios as $title => $audio){
                if ($playlist_repo->titleAvailable($playlist->getId(),$title)){
                    $audioFile=$audio_repo->findOneById($audio);
                    
                    $playlist_audio=new PlaylistAudio();
                    $playlist_audio->setAudio($audioFile);
                    $playlist_audio->setPlaylist($playlist);
                    $playlist_audio->setSortOrder(0);                
                    $playlist_audio->setTitle($title);
                    $em->persist($playlist_audio);
                    $data->add(array("success" => $success));
                }
                else{
                    $errors[]=$title." existe déjà dans la playlist et n'a pas été ajouté.";
                }
            }
            $em->flush();
            
            $success[]="L'ajout des morceaux dans ".$playlist_title." à bien été pris en compte.";
            
            $data->add(array("success" => $success));
            $data->add(array("errors" => $errors));
            
            $data = $this->container->get('serializer')->serialize($data, 'json');
            return new JsonResponse(array('data' => $data));
        }
        else {
            return $this->indexAction($request);
        }
    }

    public function createAudioCategoryAction(Request $request){
            $data=array();
        
            $audio_cat = new AudioCategory();
            $em = $this -> getDoctrine() -> getManager();
            $repo = $em->getRepository('LCVPlaylistBundle:AudioCategory');
            $data=$request->request->get("form");             
            $audio_cat->setName($data['name']);
            $parent=$repo->findOneById($data['parent']);
            $repo -> persistAsLastChildOf($audio_cat,$parent);
            
            $em -> flush();

            $request -> getSession() -> getFlashBag() -> add('success', 'Catégorie bien enregistrée !');
            return $this -> redirect($this -> generateUrl('lcv_platlist_audio_index'));
    }
    
    public function deleteAudioAction(Request $request){
        if($request->isXmlHttpRequest())
        {
            $data = $request->request;
            $audios = $data->get('audios');
            $categories = $data->get('category');
            $em = $this->container->get('doctrine')->getEntityManager();
            $audio_repo = $repo = $em->getRepository('LCVPlaylistBundle:AudioFile');
            $audio_cat_repo = $repo = $em->getRepository('LCVPlaylistBundle:AudioCategory');
            
            $success=array();
            $errors=array();
            dump($data);
            if ($audios != null){
                foreach ($audios as $title => $audio){
                    if (!$audio_repo->isInPlaylist($audio)){
                        $audioFile=$audio_repo->findOneById($audio);
                        $em->remove($audioFile);
                    }
                    else{
                        $request -> getSession() -> getFlashBag() -> add('danger', $title." est dans une playlist et n'a pas été supprimé.");
                    }
                }
                $request -> getSession() -> getFlashBag() -> add('success', "La demande de suppression de fichier à été prise en compte");
                
            }
            if ($categories!=null){
                foreach ($categories as $category){
                        $cat=$audio_cat_repo->findOneById($category);
                        if (!$cat->getRemovable()){
                          $request -> getSession() -> getFlashBag() -> add('danger', "Vous n'êtes pas autorisé a supprimer la catégory ". $cat->getName()." !");
                        }
                        else
                         $em->remove($cat);
                    }
                    $request -> getSession() -> getFlashBag() -> add('success', 'La demande de suppression de category à été prise en compte !');
                }
            
            
            $em->flush();
            
            $data->add(array("success" => $success));
            $data->add(array("errors" => $errors));
            
            $data = $this->container->get('serializer')->serialize($data, 'json');
            return new JsonResponse(array('data' => $data));
        }
        else {
            return $this->indexAction($request);
        }
    }

    public function downloadAudioAction(Request $request){
        if($request->isXmlHttpRequest())
        {
            $data = $request->request;
            $audios = $data->get('audios');
            $em = $this->container->get('doctrine')->getEntityManager();
            $audio_repo = $repo = $em->getRepository('LCVPlaylistBundle:AudioFile');
            
            $audio_dir=$this->container->getParameter('audioRootDir');
            
            $success=array();
            $errors=array();
            
            $zip = new ZipArchive();
            $path='/audios.zip';
            if (empty($audios))
                return $this->indexAction($request);
            if ($zip->open($path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) === TRUE){
                foreach ($audios as $title => $audio){
                    $audioFile=$audio_repo->findOneById($audio);
                    if (file_exists("c:\wamp\www\Le_Cerf_Vert/web/uploads/audio/55fd66c039a66.mp3"))
                       { 
                        $zip->addFile("/Le_Cerf_Vert/web/uploads/audio/55fd66c039a66.mp3");
                       }
                    else{
                        dump("c:\wamp\www\Le_Cerf_Vert/web/uploads/audio/55fd66c039a66.mp3"." file does not exist");
                    }
                }
                $zip->close();
            }
            else {
                 $errors[]="impossible de créer l'archive";
             }
            $response = new Response();
            
            $response->headers->set('Content-Type','application/zip');

            // Send headers before outputting anything
            $response->sendHeaders();
            $response->headers->set('Pragma', "no-cache");
            $response->headers->set('Expires', "0");
            $response->headers->set('Content-Transfer-Encoding', "binary");
            $response->headers->set('Cache-Control', 'private');
            $response->setContent(readfile($path));
            
            return $response;
        }
        else {
            return $this->indexAction($request);
        }
    }

}
