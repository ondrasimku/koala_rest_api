<?php

namespace App\Controller\Api;

use App\Exception\InvalidItemsPerPageException;
use App\Exception\InvalidPageNumberException;
use App\Response\ApiResponse;
use App\Service\CharacterService\CharacterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: "api_characters_")]
class CharacterController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CharacterService $characterService
    ) {
    }

    /**
     * @throws InvalidPageNumberException
     * @throws InvalidItemsPerPageException
     */
    #[Route('/characters', name: 'get_all')]
    public function getAll(): Response
    {
        $charactersPaginatedResult = $this->characterService->getCharacters();
        $responseData = $this->serializer->serialize(
            $charactersPaginatedResult->getData(),
            "json",
            [
                "groups" => ["character", "character_nemeses", "nemesis", "nemesis_secrets", "secret"],
            ]
        );
        $responseMetadata = $this->serializer->serialize($charactersPaginatedResult->getMetadata(), "json");
        return new ApiResponse($responseData, Response::HTTP_OK, metadata: $responseMetadata);
    }

    #[Route('/characters/statistics', name: 'get_statistics')]
    public function getStatistics(): Response
    {
        $characterStatistics = $this->characterService->getCharacterStatistics();
        $responseData = $this->serializer->serialize($characterStatistics, "json");
        return new ApiResponse($responseData, Response::HTTP_OK);
    }
}
