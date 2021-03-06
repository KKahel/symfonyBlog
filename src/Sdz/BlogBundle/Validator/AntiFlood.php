<?php

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AntiFlood extends Constraint
{
    public $messageSpam = 'Vous avez déjà posté un message il y a moins de 15 secondes, merci d\'attendre un peu.';

    public function validateBy()
    {
        return 'sdzblog_antiflood';
    }
}