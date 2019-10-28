<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Entity\Offer;
use App\Form\BidType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $this->denyAccessUnlessGranted("ROLE_USER");

        $offer = new Offer();
        $offer
            ->setAuction($auction)
            ->setType(Offer::TYPE_BUY)
            ->setPrice($auction->getPrice());

        $auction
            ->setStatus(Auction::STATUS_FINISHED)
            ->setExpiresAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($auction);
        $entityManager->persist($offer);
        $entityManager->flush();

        $this->addFlash("success", "You bought a {$auction->getTitle()} for {$offer->getPrice()} Euro.");

        return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
    }

    /**
     * @Route("/auction/bid/{id}", name="offer_bid", methods={"POST"})
     *
     * @param Request $request
     * @param Auction $auction
     * @return RedirectResponse
     */
    public function bidAction(Request $request, Auction $auction)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $offer = new Offer();
        $bidForm = $this->createForm(BidType::class, $offer);

        $bidForm->handleRequest($request);

        if ($bidForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $lastOffer = $entityManager
                ->getRepository(Offer::class)
                ->findOneBy(["auction" => $auction], ["createdAt" => "DESC"]);

            if (isset($lastOffer)) {
                if ($offer->getPrice() <= $lastOffer->getPrice()) {
                    $this->addFlash(
                        "error",
                        "Your price is to low. It must be higher than {$lastOffer->getPrice()} Euro"
                    );

                    return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
                }
            } else {
                if ($offer->getPrice() < $auction->getStartingPrice()) {
                    $this->addFlash("error", "Your price cannot be low than starting price.");

                    return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
                }
            }

            $offer
                ->setType(Offer::TYPE_BID)
                ->setAuction($auction);

            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash(
                "success",
                "You have submitted an offer for a {$auction->getTitle()} for {$offer->getPrice()} Euro."
            );
        } else {
            $this->addFlash("error", "Failed bidding :(");
        }

        return $this->redirectToRoute("auction_details", ["id" => $auction->getId()]);
    }
}