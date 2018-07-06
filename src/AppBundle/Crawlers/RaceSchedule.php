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
        $xPathRule   = '//table[@class="tablesorter"]/tbody[1]/tr';
        $nodeValue[] = $nodes->filterXPath($xPathRule)->each(function (Crawler $node){
            $race = [
                "race_image"  => parent::saveImage('', $node->filterXPath('//td[1]/a/img')->attr("src")),
                "horse_name" => (!empty($node->filterXPath("//td[3]/a")->text())    ? $node->filterXPath("//td[3]/a")->text() : ""),
                "horse_age"  => (!empty($node->filterXPath("//td[4]")->text())      ? trim($node->filterXPath("//td[4]")->text()) : ""),
                "dad_name"   => (!empty($node->filterXPath("//td[5]/a[1]")->text()) ? $node->filterXPath("//td[5]/a[1]")->text() : ""),
                "mom_name"   => (!empty($node->filterXPath("//td[5]/a[2]")->text()) ? $node->filterXPath("//td[5]/a[2]")->text() : ""),
                "kilogram"   => (!empty($node->filterXPath("//td[6]")->text())      ? trim($node->filterXPath("//td[6]")->text()): ""),
                "jokey"      => (!empty($node->filterXPath("//td[7]/a")->text())    ? $node->filterXPath("//td[7]/a")->text() : ""),
                "owner"      => (!empty($node->filterXPath("//td[8]/a")->text())    ? $node->filterXPath("//td[8]/a")->text() : ""),
                "trainer"    => (!empty($node->filterXPath("//td[9]/a")->text())    ? $node->filterXPath("//td[9]/a")->text() : ""),
                "start_id"   => (!empty($node->filterXPath("//td[10]")->text())     ? trim($node->filterXPath("//td[10]")->text()) : ""),
                "hc"         => (!empty($node->filterXPath("//td[11]")->text())     ? trim($node->filterXPath("//td[11]")->text()) : ""),
                "last_race"  => (!empty($node->filterXPath("//td[12]")->text())     ? trim($node->filterXPath("//td[12]")->text()) : ""),
                "kgs"        => (!empty($node->filterXPath("//td[13]")->text())     ? trim($node->filterXPath("//td[13]")->text()) : ""),
                "s20"        => (!empty($node->filterXPath("//td[14]")->text())     ? trim($node->filterXPath("//td[14]")->text()) : ""),
                "best20"     => (!empty($node->filterXPath("//td[15]/a")->text())   ? trim($node->filterXPath("//td[15]/a")->text()) : ""),
                "agf"        => (!empty($node->filterXPath("//td[16]")->text())     ? trim($node->filterXPath("//td[16]")->text())   : ""),
            ];

            dump($race);
            die;
        });
    }

    private function textRen($text){

    }
}