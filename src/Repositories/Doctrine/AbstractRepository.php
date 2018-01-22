<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AbstractRepository.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace Jitesoft\OAuth\Lumen\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

class AbstractRepository {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
}
