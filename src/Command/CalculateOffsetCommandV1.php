<?php


namespace App\Command;


use App\Service\OffsetCalculatorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateOffsetCommandV1 extends Command
{
    protected static $defaultName = 'app:calculate-offset-v1';
    private $offsetCalculatorService;

    public function __construct(OffsetCalculatorService $offsetCalculatorService)
    {
        $this->offsetCalculatorService = $offsetCalculatorService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Output the offset given a date, from the database')
            ->addArgument('year');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        if ($year) {
            $this->outputOffsetForYear($output, $year);
        } else {
            $output->writeln('You did not give a specific year...');
            for ($i=2020; $i<=2050; $i=$i+10) {
                $this->outputOffsetForYear($output, $i);
            }
        }

        return Command::SUCCESS;
    }

    private function outputOffsetForYear(OutputInterface $output, $year) {
        $carbonOffset = $this->offsetCalculatorService->getCarbonOffsetsByYearV1($year);
        $output->writeln(sprintf('The %% Offset for each type in %s is :', $year));
        $output->writeln('');
        $output->writeln(sprintf('%d%% : Type 1 & 2, short-lived avoided emissions & emission reductions', $carbonOffset->getShortEmissionPercentage()));
        $output->writeln(sprintf('%d%% : Type 4, short-lived carbon removal', $carbonOffset->getShortRemovalPercentage()));
        $output->writeln(sprintf('%d%% : Type 3, short-lived emission reductions', $carbonOffset->getLongEmissionPercentage()));
        $output->writeln(sprintf('%d%% : Type 5, long-lived carbon removal', $carbonOffset->getLongRemovalPercentage()));
        $output->writeln('');
        $output->writeln('');
    }
}