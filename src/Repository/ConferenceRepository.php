<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ConferenceRepository
 *
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['year' => 'ASC', 'city' => 'ASC']);
    }

    public function save(Conference $conference)
    {
        $this->getEntityManager()->persist($conference);
        $this->getEntityManager()->flush($conference);
    }

    /**
     * @param string $conferenceId
     * @return Conference|object
     * @throws EntityNotFoundException
     */
    public function getByIdOrFail(string $conferenceId): object
    {
        $conference = $this->findBy(['id' => $conferenceId]);
        if (!$conference) {
            throw new EntityNotFoundException("The conference with id '{$conferenceId}' could not be found");
        }

        return $conference[0];
    }
}
