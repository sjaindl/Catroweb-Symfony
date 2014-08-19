<?php

namespace Catrobat\CoreBundle\Services;


use Catrobat\CoreBundle\Entity\InsultingWords;
use Symfony\Component\Security\Core\Util\StringUtils;

class BadWordFilter {

    public function validate($word)
    {
        $repository = $this->getDoctrine()
            ->getRepository('src\Catrobat\CoreBundle\Entity\InsultingWords');
        $insultingWord = $repository->findOneByName($word);
        if(!$insultingWord)
        {
            throw $this->createNotFoundException('No word found '.$word);
        }
        else
        {
            return true;
        }
    }
} 