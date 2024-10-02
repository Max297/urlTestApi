<?php 


namespace App\Service;
use App\Repository\UrlRepository;
class Stat
{

    private $UrlRepository;

    public function __construct(UrlRepository $UrlRepository)
    {
        $this->UrlRepository = $UrlRepository;
    }
    public function getStat($url,$begin, $end)
    {
        $domainRes="enter url param ";
        $perRes="enter begin and end params ";

        if ( null !== $url){
            $domain=parse_url($url)['host'];
            $domainRes = $this->UrlRepository->countUniqueByDomain($domain);
        }
        if (null !==$begin &&  null !==$end ){

           

           

            $begin = date('Y-m-d H:i:s.ms', $begin);
            
        
            $end = date('Y-m-d H:i:s.ms', $end);

            
            $perRes = $this->UrlRepository->countInPeriod($begin, $end);
        }
        return [
            'domainCount' => $domainRes,
            'periodCount' => $perRes
        ];


        #return $this->UrlRepository->countInPeriod($begin, $end);
    }
}
?>