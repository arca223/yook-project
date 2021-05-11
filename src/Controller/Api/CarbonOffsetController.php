<?php


namespace App\Controller\Api;


use App\Service\OffsetCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class CarbonOffsetController extends AbstractController
{
    private $offsetCalculatorService;

    public function __construct(OffsetCalculatorService $offsetCalculatorService)
    {
        $this->offsetCalculatorService = $offsetCalculatorService;
    }

    /**
     * Gives all the carbon offsets
     *
     * @Route("/api/offsets}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Gives the carbon offsets",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=CarbonOffset::class, groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="CarbonOffsets")
     *
     */
    public function carbonOffsetsAction()
    {
        $carbonOffsets = $this->offsetCalculatorService->getAllCarbonOffsets();

        $jsonResponseData = [];
        foreach ($carbonOffsets as $carbonOffset) {
            $jsonResponseData[] = [
                $carbonOffset->getYear() => [
                    "Type 1, 2 short-lived storage" => $carbonOffset->getShortEmissionPercentage().'%',
                    "Type 4 short-lived storage" => $carbonOffset->getShortRemovalPercentage().'%',
                    "Type 3 long-lived storage" => $carbonOffset->getLongEmissionPercentage().'%',
                    "Type 5 long-lived storage" => $carbonOffset->getLongRemovalPercentage().'%',
                ]
            ];
        }
        return new JsonResponse(
            $jsonResponseData
        );
    }

    /**
     * Gives all the offset for a given date
     *
     * @Route("/api/offsets/{year}", methods={"GET"})
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
     * @OA\Tag(name="CarbonOffsetByYear")
     *
     */
    public function carbonOffsetsByYearAction(int $year)
    {
        $offsetCarbon = $this->offsetCalculatorService->getCarbonOffsetsByYearV1($year);
        return new JsonResponse(
            [
                "Type 1, 2 short-lived storage" => $offsetCarbon->getShortEmissionPercentage().'%',
                "Type 4 short-lived storage" => $offsetCarbon->getShortRemovalPercentage().'%',
                "Type 3 long-lived storage" => $offsetCarbon->getLongEmissionPercentage().'%',
                "Type 5 long-lived storage" => $offsetCarbon->getLongRemovalPercentage().'%',
            ]
        );
    }
}