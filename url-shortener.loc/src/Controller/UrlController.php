<?php

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
use App\Service\Stat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractController
{


    private $statService;

    public function __construct(Stat $statService)
    {
        $this->statService = $statService;
    }

    /**
     * @Route("/encode-url", name="encode_url")
     */
    public function encodeUrl(Request $request): JsonResponse
    {
        if (filter_var($request->get('url'), FILTER_VALIDATE_URL)) {
            /** @var UrlRepository $urlRepository */
            $urlRepository = $this->getDoctrine()->getRepository(Url::class);

            $urlCheck=$urlRepository->findOneByUrl($request->get('url'));
            if (is_null($urlCheck)) {
                $url = new Url();
                $url->setUrl($request->get('url'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($url);
                $entityManager->flush();

                return $this->json([
                    'hash' => $url->getHash()
                ]);
            }
            else {
                return $this->json([
                    'hash' => $urlCheck->getHash()
                ]);
            }
        }
        else{
            return $this->json([
                'response' => "incorrect URL"
            ]);
        }
    }

    /**
     * @Route("/decode-url", name="decode_url")
     */
    public function decodeUrl(Request $request): JsonResponse
    {
        /** @var UrlRepository $urlRepository */
        $urlRepository = $this->getDoctrine()->getRepository(Url::class);
        $url = $urlRepository->findOneByHash($request->get('hash'));
        if (empty ($url)) {
            return $this->json([
                'error' => 'Non-existent hash.'
            ]);
        }
        else{
            if (time()>$url->getEndDate()->getTimestamp()){
                return $this->json([
                    'error' => 'hash expired.'
                ]);
            }
            else{
                return $this->json([
                    'url' => $url->getUrl()
                ]);
            }
        }
    }


    #Функция редиректа на декодируемый url, в качестве параметра принимает hash. Если url  с таким hash нет, возвращяет сообщение
    /**
     * @Route("/go-url", name="go_url")
     */
    public function goUrl(Request $request)
    {
        

        /** @var UrlRepository $urlRepository */
        $urlRepository = $this->getDoctrine()->getRepository(Url::class);
        $url = $urlRepository->findOneByHash($request->get('hash'));
        if (empty ($url)) {
            return $this->json([
                'error' => 'Non-existent hash.'
            ]);
        }
        
        return $this->redirect($url->getUrl());
    }


    #Полученная информация передается в сервис Stat, begin и end могут быть только timestamp
    /**
     * @Route("/get-stat", name="get_stat")
     */
    public function getStat(Request $request)
    {
        


        if (is_numeric($request->get('begin'))  && is_numeric($request->get('end')) ){
            $results = $this->statService->getStat($request->get('url'),$request->get('begin'), $request->get('end'));
            
            return $this->json([
                'domainCount' => $results['domainCount'],
                'periodCount' => $results['periodCount']
            ]);
        }
        else {
            return $this->json([
                'error' => "end and begin params must be timestamps"
            ]);
        }
    }

    
}
