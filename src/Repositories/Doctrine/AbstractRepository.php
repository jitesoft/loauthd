<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  AbstractRepository.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractRepository
 *
 * Base class for doctrine repositories.
 */
class AbstractRepository {

    /** @var EntityManagerInterface */
    protected $em;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger) {
        $this->logger = $logger;
        $this->em     = $em;
    }

}
