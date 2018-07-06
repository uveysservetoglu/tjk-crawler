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
    private function crawl(){

        self::getRace();
    }

    private function getRace(){

        $url = "http://www.tjk.org/TR/YarisSever/Info/Page/GunlukYarisProgrami";
        //$xPathRule = '//div[@class="container"]';
        $crawler = new Crawler(file_get_contents($url));
    }
}