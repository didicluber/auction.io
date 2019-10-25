<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends Controller
{
    /**
     * @Route("/auction/buy/{id}", name="offer_buy", methods={"POST"})
     *
     * @param Auction $auction
     * @return RedirectResponse
     */
    public function buyAction(Auction $auction)
    {
        $offer = new Offer();
        $offer
            ->setAuction($auction)
            ->setType(Offer::TYPE_BUY)
            ->setPrice($auction->getPrice());

        $auction->setStatus(Auction::STATUS_FINISHED);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($auction);
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
    }
}