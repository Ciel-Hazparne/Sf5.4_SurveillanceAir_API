<?php

namespace App\Command;

use App\Entity\CO2;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldCO2DataCommand extends Command
{
    protected static $defaultName = 'app:delete-old-co2-data';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Deletes CO2 data older than one month.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oneMonthAgo = new \DateTime('-1 month');
        $repository = $this->entityManager->getRepository(CO2::class);
        $oldData = $repository->createQueryBuilder('c')
            ->where('c.date < :oneMonthAgo')
            ->setParameter('oneMonthAgo', $oneMonthAgo)
            ->getQuery()
            ->getResult();
        foreach ($oldData as $data) {
            $this->entityManager->remove($data);
        }
        $this->entityManager->flush();
        $output->writeln(sprintf('Deleted %d old CO2 data records.', count($oldData)));

        return 0;
    }
}
