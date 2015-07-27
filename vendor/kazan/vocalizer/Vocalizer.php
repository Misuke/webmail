<?php

namespace Kazan\Vocalizer;

class Vocalizer
{
    private $voxygenUrl = 'http://voxygen.fr';
    private $cacheFolder = 'cache';
    private $voices = array();

    public function __construct()
    {
        $this->getVoices();
    }

    public function synthesizeText($lang, $text)
    {
        if (!array_key_exists($lang, $this->voices)) {
            throw new \Exception('The lang you\'ve selected is not implemented yet');
        }

        if (!is_dir($this->cacheFolder)) {
            mkdir($this->cacheFolder);
        }

        $text = (get_magic_quotes_gpc()) ? stripslashes($text) : $text;
        $filename = $this->cacheFolder .'/'. sha1($lang.$text) .'.mp3';

        if (!file_exists($filename)) {
            $postData = 'method=redirect&voice='. $this->voices[$lang] .'&text='. urlencode($text) .'&ts='. time();
            $voxygenResult = $this->curlJob($postData);

            if ($voxygenResult !== null) {
                if (!file_put_contents($filename, $voxygenResult)) {
                    throw new \Exception('Can\'t create audio file : '. $filename);
                }
            } else {
                throw new \Exception('Can\'t generate audio file with Voxygen APIs.');   
            }
        }

        return $filename;
    }

    private function curlJob($postData)
    {
        $curlHandler = curl_init($this->voxygenUrl.'/sites/all/modules/voxygen_voices/assets/proxy/index.php');
        curl_setopt($curlHandler, CURLOPT_HEADER, false);
        curl_setopt($curlHandler, CURLOPT_POST, true);
        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandler, CURLOPT_REFERER, $this->voxygenUrl.'/fr');
        curl_setopt($curlHandler, CURLOPT_USERAGENT, 'iTunes/9.0.3 (Macintosh; U; Intel Mac OS X 10_6_2; en-ca)');
        curl_setopt($curlHandler, CURLOPT_COOKIE, true);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array(
            'Content-type: application/x-www-form-urlencoded',
            'X-Requested-With: XMLHttpRequest',
            'Host: voxygen.fr'
        ));

        $output = curl_exec($curlHandler);
        curl_close($curlHandler);
        return $output;
    }

    private function getVoices()
    {
        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:23.0) Gecko/20100101 Firefox/23.0';
        $voices    = array();

        $curlHandler = curl_init($this->voxygenUrl.'/voices.json');
        curl_setopt($curlHandler, CURLOPT_REFERER, $this->voxygenUrl.'/fr');
        curl_setopt($curlHandler, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curlHandler);
        curl_close($curlHandler);

        $voices = json_decode($output, true);
        if ($voices === null) {
            throw new \Exception('Voxygen voices is not a valid JSON result');
        }
        if (isset($voices['groups']) && is_array($voices['groups'])) {
            foreach ($voices['groups'] as $lang) {
                if (isset($lang['code'])) {
                    if (isset($lang['voices']) && is_array($lang['voices'])) {
                        foreach ($lang['voices'] as $voice) {
                            if (isset($voice['name'])) {
                                $voices[$voice['name']] = $lang['code'];
                            }
                        }
                    }
                }
            }
        }

        if (count($voices) == 0) {
            throw new \Exception('Can\'t get voices on Voxygen site.');
            
        }

        $this->filterVoices($voices);
    }

    private function filterVoices(array $voices)
    {
        foreach ($voices as $name => $lang) {
            if ($lang == 'en') {
                if (!array_key_exists($lang, $this->voices) || $this->voices[$lang] != 'Elizabeth') {
                    $this->voices[$lang] = $name;
                }
            }
            if ($lang == 'fr') {
                if (!array_key_exists($lang, $this->voices) || $this->voices[$lang] != 'Agnes') {
                    $this->voices[$lang] = $name;
                }
            }
        }
    }
}
