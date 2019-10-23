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
                "id" => 1,
                "title" => "Excellent car",
                "description" => "Simple excellent car description",
                "price" => "1000 zł"
            ],
            [
                "id" => 2,
                "title" => "Bike",
                "description" => "Simple bike description",
                "price" => "100 zł"
            ],
            [
                "id" => 3,
                "title" => "Dishwasher",
                "description" => "Simple dishwasher description",
                "price" => "300 zł"
            ]
        ];

        return $this->render("Auction/index.html.twig", ["auctions" => $auctions]);
    }

    /**
     * @Route("/{id}", name="auction_details")
     *
     * @param $id
     */
    public function detailsAction($id)
    {
        return $this->render("Auction/details.html.twig");
    }
}