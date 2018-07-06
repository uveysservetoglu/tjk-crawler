<?php
/**
 * Created by PhpStorm.
 * User: Uveys-Mac
 * Date: 30.05.2018
 * Time: 13:41
 */
namespace AppBundle\Crawlers;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
class BaseCrawler
{
    public $kernel;
    public $rootDir;
    public $fs;

    /**
     * BaseCrawler constructor.
     * @param $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
        $this->rootDir = $kernel->getRootDir();
        $this->fs = new Filesystem();
    }

    public function tagContent($link, $xPathRule){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->text();
    }

    public function getHtml($link, $xPathRule){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->html();
    }

    public function crawlAttr($link, $xPathRule, $attr){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->attr($attr);
    }
}