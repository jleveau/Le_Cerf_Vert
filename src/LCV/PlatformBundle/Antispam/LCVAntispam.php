<?php

namespace LCV\PlatformBundle\Antispam;

class OCAntispam extends \Twig_Extension {
    private $mailer;
    private $locale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minLength) {
        $this -> mailer = $mailer;
        $this -> locale = $locale;
        $this -> minLength = (int)$minLength;
    }

    public function isSpam($text) {
        return strlen($text) < $this -> minLength;
    }

    public function getFunctions() {
        return array('checkIfSpam' => new \Twig_Function_Method($this, 'isSpam'));
    }

    // La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName() {
        return 'LCVAntispam';
    }

}
