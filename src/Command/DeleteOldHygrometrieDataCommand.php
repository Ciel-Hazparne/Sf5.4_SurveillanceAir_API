<?php

namespace App\Command;

use App\Entity\Hygrometrie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldHygrometrieDataCommand extends Command
{
    protected static $defaultName = 'app:delete-old-hygrometrie-data';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Deletes hygrometrie data older than one month.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oneMonthAgo = new \DateTime('-1 month');
        $repository = $this->entityManager->getRepository(Hygrometrie::class);
        $oldData = $repository->createQueryBuilder('h')
            ->where('h.date < :oneMonthAgo')
            ->setParameter('oneMonthAgo', $oneMonthAgo)
            ->getQuery()
            ->getResult();
        foreach ($oldData as $data) {
            $this->entityManager->remove($data);
        }
        $this->entityManager->flush();
        $output->writeln(sprintf('Deleted %d old hygrometrie data records.', count($oldData)));

        return 0;
    }
}