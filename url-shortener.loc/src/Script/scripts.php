<?php

namespace App\Script;

use App\Entity\Url;
use App\Repository\UrlRepository;
use App\Service\Stat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class scripts 
{


    private $statService;

    public function __construct(Stat $statService)
    {
        $this->statService = $statService;
    }


    public function sendInfo(){
        echo "1123123123";
        
        $toSend=$this->statService->UrlRepository->findUnsent();

        foreach ($toSend as $elem ) {

            $url = 'http://url-shortener.loc/get-stat';
            $data = ['url' => $elem->getUrl(), 'begin' => $elem->getCreatedDate(),'end'=>$elem->getCreatedDate()];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            $response = curl_exec($ch);
            curl_close($ch);

        }
        $this->statService->UrlRepository->fillUnsent();

        
        
    }
    
}