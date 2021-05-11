<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CarbonOffset;

class OffsetFixtures extends Fixture
{
    const OFFSETS_BY_YEAR = [
        2020 => [
            'SEP' => 55,
            'SRP' => 45,
            'LEP' => 0,
            'LRP' => 0,
        ],
        2030 => [
            'SEP' => 20,
            'SRP' => 40,
            'LEP' => 20,
            'LRP' => 15,
        ],
        2040 => [
            'SEP' => 10,
            'SRP' => 25,
            'LEP' => 30,
            'LRP' => 35,
        ],
        2050 => [
            'SEP' => 0,
            'SRP' => 0,
            'LEP' => 0,
            'LRP' => 100,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $arrayYears = [2020, 2030, 2040, 2050];
        foreach ($arrayYears as $year)
        {
            $carbonOffset = (new CarbonOffset())
                ->setDate((new \DateTime('Y'))->setDate($year, 1, 1))
                ->setYear($year)
                ->setShortEmissionPercentage(self::OFFSETS_BY_YEAR[$year]['SEP'])
                ->setShortRemovalPercentage(self::OFFSETS_BY_YEAR[$year]['SRP'])
                ->setLongEmissionPercentage(self::OFFSETS_BY_YEAR[$year]['LEP'])
                ->setLongRemovalPercentage(self::OFFSETS_BY_YEAR[$year]['LRP'])
            ;
            $manager->persist($carbonOffset);
        }

        $manager->flush();
    }
}
