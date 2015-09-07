<?php
namespace LCV\PlaylistBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ExtensionValidator extends ConstraintValidator {
    public $message = 'Le fichier "%string%" doit être un .mp3, .ogg, .wav';

    public function validate($value, Constraint $constraint) {
        var_dump("in");
        if (!preg_match('/\.(ogg|mp3|wav)$/i', $value, $matches)) {
            $this -> context -> addViolation($constraint -> message, array('%string%' => $value));
        }
    }

    public function validatedBy() {
        return 'LCV_Extention_validation';
    }

}
