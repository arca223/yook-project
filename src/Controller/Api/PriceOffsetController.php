<?php


namespace App\Controller\Api;


use App\Service\OffsetCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

/**
 * @Route("/api")
 */
class PriceOffsetController extends AbstractController
{
    private $offsetCalculatorService;

    public function __construct(OffsetCalculatorService $offsetCalculatorService)
    {
        $this->offsetCalculatorService = $offsetCalculatorService;
    }

    /**
     * Gives the partners offsets for a given price and date
     *
     * @Route("/price-offsets/{year}/{price}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Gives the offset for a given year",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=CarbonOffset::class, groups={"full"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="year",
     *     in="query",
     *     description="The year to fetch offsets",
     *     @OA\Schema(type="int"),
     *
     * )
     * @OA\Parameter(
     *     name="price",
     *     in="query",
     *     description="The budget to split between partners",
     *     @OA\Schema(type="int"),
     *
     * )
     * @OA\Tag(name="CarbonOffsetByYear")
     *
     */
    public function price(int $year, int $price)
    {
        return new JsonResponse($this->offsetCalculatorService->calculateBudgetForPartners($price, $year));
    }
}