<?php


namespace App\Service;

use App\Entity\CarbonOffset;
use App\Repository\CarbonOffsetRepository;

class OffsetCalculatorService
{
    private $partnerService;
    private $carbonOffsetRepository;

    const TYPE_OFFSET = [
        1 => 'shortEmissionPercentage',
        2 => 'shortEmissionPercentage',
        3 => 'shortRemovalPercentage',
        4 => 'longEmissionPercentage',
        5 => 'longRemovalPercentage',
    ];

    public function __construct(PartnerService $partnerService, CarbonOffsetRepository $carbonOffsetRepository)
    {
        $this->partnerService = $partnerService;
        $this->carbonOffsetRepository = $carbonOffsetRepository;
    }

    public function getAllCarbonOffsets(): array
    {
        return $this->carbonOffsetRepository->findAll();
    }

    public function getCarbonOffsetsByYearV1($year): CarbonOffset
    {
        return $this->carbonOffsetRepository->find($year);
    }

    public function calculateBudgetForPartners($price, $year): array
    {
        $carbonOffsets = $this->getCarbonOffsetsByYearV1($year);
        $partnersByType = $this->partnerService->getFilteredPartners();

        $budgets = [];
        foreach ($partnersByType as $type => $partners) {
            $count = (1 || 2 === $type) ? $this->partnerService->countType12Partners() : count($partners);
            $budget = $this->splitBudget($price, $carbonOffsets->__call(self::TYPE_OFFSET[$type]), $count);
            foreach ($partners as $partner) {
                $budgets[$partner['partnerName'].' '.$partner['country']] = $budget;
            }
        }

        return $budgets;
    }

    private function splitBudget($price, $percentage, $partnersCount): float
    {
        return round(($price * ($percentage/100)) / $partnersCount, 2);
    }
}