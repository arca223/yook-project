<?php


namespace App\Service;

use App\Repository\CarbonOffsetRepository;

class OffsetCalculatorService
{
    private $carbonOffsetRepository;

    public function __construct(CarbonOffsetRepository $carbonOffsetRepository)
    {
        $this->carbonOffsetRepository = $carbonOffsetRepository;
    }

    public function getAllCarbonOffsets()
    {
        return $this->carbonOffsetRepository->findAll();
    }

    public function getCarbonOffsetsByYearV1($year)
    {
        return $this->carbonOffsetRepository->find($year);
    }
}