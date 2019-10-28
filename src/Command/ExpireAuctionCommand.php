<?php

namespace App\Command;

use App\Entity\Auction;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExpireAuctionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName("app:expire_auction")
            ->setDescription("Command for finishing auctions");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get("doctrine.orm.entity_manager");
        $auctions = $entityManager->getRepository(Auction::class)->findActiveExpired();

        $output->writeln(sprintf("Found <info>%d</info> auctions to finish", count($auctions)));

        foreach ($auctions as $auction) {
            $auction->setStatus(Auction::STATUS_FINISHED);
            $entityManager->persist($auction);
        }

        $entityManager->flush();
        $output->writeln("Task completed!");
    }
}