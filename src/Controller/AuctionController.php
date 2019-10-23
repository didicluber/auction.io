<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends Controller
{
    /**
     * @Route("/", name="auction_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        $auctions = [
            [
                "title" => "Excellent car",
                "description" => "Simple excellent car description",
                "price" => "1000 zł"
            ],
            [
                "title" => "Bike",
                "description" => "Simple bike description",
                "price" => "100 zł"
            ],
            [
                "title" => "Dishwasher",
                "description" => "Simple dishwasher description",
                "price" => "300 zł"
            ]
        ];

        return $this->render("Auction/index.html.twig", ["auctions" => $auctions]);
    }
}