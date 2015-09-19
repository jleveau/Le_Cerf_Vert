<?php
namespace LCV\PlaylistBundle\EventListener;

use Oneup\UploaderBundle\Event\ValidationEvent;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;

class AudioValidatorListener
{
    public function onValidate(ValidationEvent $event)
    {
        $extension    = $event->getFile()->getExtension();
        dump($extension);
        if ($extension != "ogg" && $extension != "mp3" &&  $extension != "wav")
              throw new ValidationException('Le fichier doit etre un fichier audio');
    }
}