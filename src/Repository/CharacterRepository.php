<?php

namespace App\Repository;

use App\Entity\Character;
use App\Exception\InvalidItemsPerPageException;
use App\Exception\InvalidPageNumberException;
use App\Service\PaginationService\PaginatedResult;
use App\Service\PaginationService\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService<Character>
     */
    private readonly PaginationService $paginationService;

    /**
     * @param ManagerRegistry $registry
     * @param PaginationService<Character> $paginationService
     */
    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
        parent::__construct($registry, Character::class);
    }

    /**
     * @return PaginatedResult<Character>
     * @throws InvalidItemsPerPageException
     * @throws InvalidPageNumberException
     */
    public function getCharacterJoinNemesisAndSecrets(): PaginatedResult
    {
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.nemeses', 'n')
            ->leftJoin('n.secrets', 's')
            ->addSelect('n', 's')
            ->orderBy('c.id', 'ASC')
            ->getQuery();

        return $this->paginationService->paginate($query);
    }

    public function getCharactersCount(): int
    {
        $entityManager = $this->getEntityManager();
        // Count of characters
        $characterCount = $entityManager->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(Character::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
        return (int)$characterCount;
    }

    /**
     * @return array<string, int>
     */
    public function getAverageAges(): array
    {
        $entityManager = $this->getEntityManager();

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('avg_character_age', 'avgCharacterAge');
        $rsm->addScalarResult('avg_nemesis_age', 'avgNemesisAge');
        $rsm->addScalarResult('overall_average_age', 'overallAverageAge');

        // This query calculates the average ages for characters and their nemeses separately.
        // For characters, it calculates age based on the difference between the current date and their 'born' year.
        // For nemeses, it directly uses the 'years' column as their age.
        // It then computes the overall average age by averaging the two resulting averages (character and nemesis).
        // Native SQL is used because DQL doesn't support the YEAR or AGE functions directly, and
        // using native SQL allows us to control the query structure for better performance optimization.
        $avgAgesQuery = $entityManager->createNativeQuery(
            "
        SELECT
            character_avg.avg_character_age,
            nemesis_avg.avg_nemesis_age,
            ROUND((character_avg.avg_character_age + nemesis_avg.avg_nemesis_age) / 2) AS overall_average_age
        FROM
            (
                SELECT
                    ROUND(AVG(EXTRACT(YEAR FROM AGE(born)))) AS avg_character_age
                FROM
                    character
            ) AS character_avg,
            (
                SELECT 
                    ROUND(AVG(years)) AS avg_nemesis_age 
                FROM 
                    nemesis
            ) AS nemesis_avg;
    ",
            $rsm
        );
        $result = $avgAgesQuery->getSingleResult();

        return [
            'average_age_characters' => (int)$result['avgCharacterAge'],
            'average_age_nemeses' => (int)$result['avgNemesisAge'],
            'average_age_overall' => (int)$result['overallAverageAge']
        ];
    }
}
