<?php

namespace App\Service\CharacterService;

use App\Entity\Character;
use App\Exception\InvalidItemsPerPageException;
use App\Exception\InvalidPageNumberException;
use App\Repository\CharacterRepository;
use App\Service\PaginationService\PaginatedResult;

readonly class CharacterService
{
    public function __construct(
        private CharacterRepository $characterRepository
    ) {
    }

    /**
     * @return PaginatedResult<Character>
     * @throws InvalidItemsPerPageException
     * @throws InvalidPageNumberException
     */
    public function getCharacters(): PaginatedResult
    {
        // We use custom query to prevent lazy loading and N+1
        // We retrieve all characters along with nemeses and secret
        return $this->characterRepository->getCharacterJoinNemesisAndSecrets();
    }

    /**
     * @return array<string, int>
     */
    public function getCharacterStatistics(): array
    {
        $averageAges = $this->characterRepository->getAverageAges();
        $charactersCount = $this->characterRepository->getCharactersCount();
        return [
            "characters_count" => $charactersCount,
            'average_age_characters' => $averageAges['average_age_characters'],
            'average_age_nemeses' => $averageAges['average_age_nemeses'],
            'average_age_overall' => $averageAges['average_age_overall']
        ];
    }
}
