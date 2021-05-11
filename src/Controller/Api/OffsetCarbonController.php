<?php


namespace App\Controller\Api;


use App\Service\OffsetCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
/**
 * @Route("/api")
 */
class OffsetCarbonController extends AbstractController
{
    private $offsetCalculatorService;

    public function __construct(OffsetCalculatorService $offsetCalculatorService)
    {
        $this->offsetCalculatorService = $offsetCalculatorService;
    }

    /**
     * Gives all the carbon offsets
     *
     * @Route("/offsets}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Gives the carbon offsets",
     *     @SWG\Schema(
     *        type="array",
     *        @SWG\Items(ref=@Model(type=CarbonOffset::class, groups={"full"}))
     *     )
     * )
     * @SWG\Tag(name="CarbonOffsets")
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
     * @Route("/offsets/{year}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Gives the offset for a given year",
     *     @SWG\Schema(
     *        type="array",
     *        @SWG\Items(ref=@Model(type=CarbonOffset::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="year",
     *     in="query",
     *     description="The year to fetch offsets",
     *     @SWG\Schema(type="int"),
     *
     * )
     * @SWG\Tag(name="CarbonOffsetByYear")
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