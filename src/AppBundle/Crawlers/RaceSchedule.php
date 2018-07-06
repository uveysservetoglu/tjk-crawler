<?php
/**
 * Created by PhpStorm.
 * User: Uveys SERVETOGLU (@uveysservetoglu)
 * Date: 6.07.2018
 * Time: 09:46
 */

namespace AppBundle\Crawlers;

use Symfony\Component\DomCrawler\Crawler;

class RaceSchedule extends BaseCrawler
{
    public function crawl(){

        self::getRace();
    }

    private function getRace(){

        $url = "http://www.tjk.org/TR/YarisSever/Info/Sehir/GunlukYarisProgrami?SehirId=4&QueryParameter_Tarih=06%2F07%2F2018&SehirAdi=Bursa";
        $crawler = new Crawler(file_get_contents($url));

        $xPathRule = '//div[@class="program"]/div[4]';
        $nodeValue[]=$crawler->filterXPath($xPathRule)->each(function (Crawler $node){

            self::races($node);

        });
    }

    private function races($nodes){

        echo "[OK] Races List GET".PHP_EOL;
        $xPathRule = '//table[@class="tablesorter"]/tbody[1]/tr';
        $nodeValue[]=$nodes->filterXPath($xPathRule)->each(function (Crawler $node){
            $imgPath = $node->filterXPath('//td[1]/a/img');
            $image   = parent::saveImage('', $imgPath->attr("src"));

        });
    }
}