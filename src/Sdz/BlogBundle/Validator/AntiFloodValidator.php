<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 25/09/2015
 * Time: 14:26
 */

namespace Sdz\BlogBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiFloodValidator extends ConstraintValidator
{
    private $request;
    private $em;

    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $ip = $this->request->getServer()->get('REMOTE_ADDR');

        // Pour l'instant, on considère comme flood tout message de moins de 3 caractères
        if (strlen($value) < 3) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->buildViolation($constraint->messageSpam)
                ->addViolation();
//            $this->context->addViolation($constraint->messageSpam);
        }
  }
}