<?php


namespace App\Controller;

use App\Service\OffsetCalculatorService;
use App\Service\PartnerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Sandbox Controller to test features on route /sandbox
 * @Route("/sandbox")
 */
class SandboxController
{
    private $testService;
    private $testService2;

    public function __construct(OffsetCalculatorService $testService, PartnerService $testService2)
    {
        $this->testService = $testService;
        $this->testService2 = $testService2;
    }

    public function __invoke()
    {
        //$parners = $this->testService->getPartners();
        //$partnersByType = $this->testService->getPartnersByType(1);
        //dd($this->testService2->getFilteredPartners());
        //dd($this->testService->getCarbonOffsetsByYearV1(2020));
        dd($this->testService->calculateBudgetForPartners(1000, 2020));

        return new Response('Sandbox Controller OK', 200);
    }
}