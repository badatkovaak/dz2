<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface as EMInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    public function getAllLinks(): array
    {
        return $this->findAll();
    }

    public function getLinkByUrl(string $shortUrl): ?Link
    {
        return $this->findOneBy(['shortUrl' => $shortUrl]);
    }

    public function getLinkById(int $id): ?Link
    {
        return $this->findBy($id);
    }

    public function saveLink(Link $link): void
    {
        $em = $this->getEntityManager();
        $em->persist($link);
        $em->flush();
    }

    public function deleteLink(Link $link): void
    {
        $em = $this->getEntityManager();
        $em->remove($link);
        $em->flush();
    }

    public function shortUrlIsUnique(string $shortUrl): bool
    {
        $links = $this->findBy(['shortUrl' => $shortUrl]);
        return count($links) === 0;
    }
}
