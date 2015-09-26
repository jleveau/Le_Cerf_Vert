<?php
namespace Application\Sonata\MediaBundle\Twig;

class MediaUrlExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mediaUrl', array($this, "getmediaUrlFilter")),
        );
    }
    
    public function getmediaUrlFilter($image, $format='small')
    {
        $imageProvider = $this->get($image->getProvider());
        $url=$imageProvider->generatePublicUrl($image, $format);
        return " ";
    }

    public function getName()
    {
        return 'mediaUrlExtension';
    }
}