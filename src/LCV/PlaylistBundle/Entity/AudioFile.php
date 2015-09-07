<?php
namespace LCV\PlaylistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use LCV\PlaylistBundle\Validator\Constraints\ExtensionValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * AudioFile
 *
 * @ORM\Table(name="lcv_playlist_files")
 * @ORM\Entity(repositoryClass="LCV\PlaylistBundle\Entity\AudioFileRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("title")
 */
class AudioFile {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255,nullable=true)
     */
    private $title;

    /** 
     * @Assert\File(
     *     maxSize = "20000000")
     * Extension
     */
    private $file;

    /**
     * @var string
     * 
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
    
     /**
     * @ORM\OneToMany(targetEntity="LCV\PlaylistBundle\Entity\PlaylistAudio", mappedBy="audio", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $playlist_audios;
    

    /**
     * @var string
     *
     * @ORM\Column(name="uploadDir", type="string", length=255, nullable=true)
     */
    private $uploadDir;
    /////
      /**
   * @Assert\Callback
   */
  public function isExtensionValid(ExecutionContextInterface $context)
  {
    // On vérifie que le contenu ne contient pas l'un des mots
    if (!preg_match('/\.*(ogg|mp3|wav)/', $this -> file->getClientOriginalName()) && $this->file!=null) {
      // La règle est violée, on définit l'erreur
      $context
        ->buildViolation('Le fichier doit etre un .mp3,.ogg ou .wav') // message
        ->atPath('file')                                                   // attribut de l'objet qui est violé
        ->addViolation() // ceci déclenche l'erreur, ne l'oubliez pas
      ;
    }
  }
  
    public function setFile($file) {
        $this -> file = $file;
    }

    public function getFile() {
        /* Note that the file is taken relatively to the web root. The initial
         * slash for the path of the picture is to be removed, else the server
         * will return a 500 error */

        if (($this -> path != NULL) && ($this -> name != NULL)) {

            $this -> file = new UploadedFile($this -> getUploadRootDir() . $this -> getUploadDir() . $this -> name, $this -> name);

            return $this -> file;
        }
        return NULL;
    }

    public function getAbsolutePath() {
        return null === $this -> path ? null : $this -> getUploadRootDir() . $this -> getUploadDir() . $this -> name;
    }

    public function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/uploads/audio/';
    }

    public function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return $this -> uploadDir;
    }

    public function upload() {
        if (null === $this -> file) {
            return;
        }
        if ($this -> name != null) {
            $old = new UploadedFile($this -> getUploadRootDir() . $this -> getUploadDir() . $this -> name, $this -> name);
            unlink($old);
        }
        // On enleve le / devant
        $this -> uploadDir =  '/';
        $this -> name = $this -> file -> getClientOriginalName();

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this -> file -> move($this -> getUploadRootDir() . $this -> getUploadDir(), $this -> file -> getClientOriginalName());

        // set the path property to the filename where you've saved the file
        $this -> path = $this -> file -> getClientOriginalName();
    }

    /**
     * The string representation for the entity
     *
     * @return text
     */
    public function __toString() {
        $class = get_called_class();
        return ($this -> path ? $this -> path : ($class::UPLOAD_DIR . '/')) . $this -> name;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $file = $this -> getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    /////

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this -> id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AudioFile
     */
    public function setName($name) {

        $this -> name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this -> name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return AudioFile
     */
    public function setPath($path) {
        $this -> path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this -> path;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return AudioFile
     */
    public function setTitle($title) {
        $this -> title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this -> title;
    }

    /**
     * Set uploadDir
     *
     * @param string $uploadDir
     * @return AudioFile
     */
    public function setUploadDir($uploadDir) {
        $this -> uploadDir = $uploadDir;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playlist_audios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add playlist_audios
     *
     * @param \LCV\PlaylistBundle\Entity\PlaylistAudio $playlistAudios
     * @return AudioFile
     */
    public function addPlaylistAudio(\LCV\PlaylistBundle\Entity\PlaylistAudio $playlistAudios)
    {
        $this->playlist_audios[] = $playlistAudios;
        $playlistAudios->setAudio($this);
        return $this;
    }

    /**
     * Remove playlist_audios
     *
     * @param \LCV\PlaylistBundle\Entity\PlaylistAudio $playlistAudios
     */
    public function removePlaylistAudio(\LCV\PlaylistBundle\Entity\PlaylistAudio $playlistAudios)
    {
        $this->playlist_audios->removeElement($playlistAudios);
    }

    /**
     * Get playlist_audios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaylistAudios()
    {
        return $this->playlist_audios;
    }
}
