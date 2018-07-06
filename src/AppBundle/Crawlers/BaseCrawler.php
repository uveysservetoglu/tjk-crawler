<?php
/**
 * Created by PhpStorm.
 * User: Uveys-Mac
 * Date: 30.05.2018
 * Time: 13:41
 */
namespace AppBundle\Crawlers;


use AppBundle\Entity\Media;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\File\File;
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

    public function saveImage($folder, $link,$preView = null){

        $media = null;

        $imageDir = $this->rootDir.'/../web/upload/'.$folder;

        if(!$this->fs->exists($imageDir)){
            $this->fs->mkdir($imageDir );
        }
        $file = explode(".",$link);
        $fileName = md5(microtime());
        $target = $imageDir.'/'.$fileName;

        $client = new GuzzleClient();
        $client->get("https://www.kolanhastanesi.com.tr".$link, array('save_to' => $target));

        if (!$this->fs->exists($target)) {
            throw new \Exception('Image of second hand vehicle could not saved to target : ' . $target);
        }

        $file = new File($target);
        $mime = $file->getMimeType();
        //Add extension to the file
        $extension = 'png';
        if (is_null($file->getExtension())) {
            $arr = explode('.', $fileName);
            if (count($arr) > 0) {
                $extension = $arr[count($arr) - 1];
            } else {
                $arr = explode('/', $file->getMimeType());
                if (count($arr) > 0) {
                    $extension = $arr[1];
                }
            }
        }
        $source = $target;
        $target .= '.' . $extension;
        $this->fs->rename($source, $target);
        $fileName .= '.'.$extension;
        //$this->createThumbs($target,$fileName);

        $media = array("url"=>$folder.'/'.$fileName, "type"=>"i", "mime"=>$mime);
        return $this->saveToDbMedia($media, $preView);

    }

    public function getYoutubeImage($folder, $code){

        $media = null;

        $imageDir = $this->rootDir.'/../web/upload/'.$folder;

        if(!$this->fs->exists($imageDir)){
            $this->fs->mkdir($imageDir );
        }
        $file = explode(".",$code);
        $fileName = md5(microtime());
        $target = $imageDir.'/'.$fileName;

        $client = new GuzzleClient();
        $client->get("https://img.youtube.com/vi/".$code."/sddefault.jpg", array('save_to' => $target));

        if (!$this->fs->exists($target)) {
            throw new \Exception('Image of second hand vehicle could not saved to target : ' . $target);
        }

        $file = new File($target);
        $mime = $file->getMimeType();
        //Add extension to the file
        $extension = 'jpg';
        if (is_null($file->getExtension())) {
            $arr = explode('.', $fileName);
            if (count($arr) > 0) {
                $extension = $arr[count($arr) - 1];
            } else {
                $arr = explode('/', $file->getMimeType());
                if (count($arr) > 0) {
                    $extension = $arr[1];
                }
            }
        }
        $source = $target;
        $target .= '.' . $extension;
        $this->fs->rename($source, $target);
        $fileName .= '.'.$extension;
        //$this->createThumbs($target,$fileName);

        $media = array("url"=>$folder.'/'.$fileName, "type"=>"y", "mime"=>$mime);


        return $media;
    }

    public function savePdf($folder, $link){

        $media = null;

        $imageDir = $this->rootDir.'/../web/upload/'.$folder;

        if(!$this->fs->exists($imageDir)){
            $this->fs->mkdir($imageDir );
        }
        $file = explode(".",$link);
        $fileName = md5(microtime());
        $target = $imageDir.'/'.$fileName;

        $client = new GuzzleClient();
        $client->get("https://www.kolanhastanesi.com.tr".$link, array('save_to' => $target));

        if (!$this->fs->exists($target)) {
            throw new \Exception('Image of second hand vehicle could not saved to target : ' . $target);
        }

        $file = new File($target);
        $mime = $file->getMimeType();
        //Add extension to the file
        $extension = 'pdf';
        if (is_null($file->getExtension())) {
            $arr = explode('.', $fileName);
            if (count($arr) > 0) {
                $extension = $arr[count($arr) - 1];
            } else {
                $arr = explode('/', $file->getMimeType());
                if (count($arr) > 0) {
                    $extension = $arr[1];
                }
            }
        }
        $source = $target;
        $target .= '.' . $extension;
        $this->fs->rename($source, $target);
        $fileName .= '.'.$extension;
        //$this->createThumbs($target,$fileName);

        $media = array("url"=>$folder.'/'.$fileName, "type"=>"f", "mime"=>$mime);
        return $media["url"];

    }

    public function saveUrl($url)
    {
        $url = str_replace("'","\'",$url);
        $sql = "
            INSERT INTO crawler (url) VALUES (:url)
        ";

        $sql = sprintf($sql,[$url]);

        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();
        $manager->getConnection()->prepare($sql)->execute([':url'=>$url]);
    }

    public function checkUrl($url)
    {
        /**
         * @var EntityManager $manager
         */
        $manager = $this->kernel->getContainer()->get('doctrine')->getManager();
        $url = str_replace("'","\'",$url);

        $query = "select * from crawler where url = :url";

        $result = $manager->getConnection()->prepare($query);

         $result->execute([':url'=>$url]);
         $data = $result->fetchAll();

        if(count($data)>0) dump('Bu kayıt zaten var : ' . $url);
        return count($data)>0 ? false : true;
    }

    public function url_make($str){
        $before = array('ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ö', 'Ç'); // , '\'', '""'
        $after   = array('i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 'o', 'c'); // , '', ''
        $clean = str_replace($before, $after, $str);
        $clean = preg_replace('/[^a-zA-Z0-9 ]/', '', $clean);
        $clean = preg_replace('!\s+!', '-', $clean);
        $clean = strtolower(trim($clean, '-'));
        return $clean;
    }
}