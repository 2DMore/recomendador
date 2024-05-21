<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvertedIndex extends Model
{
    private $stopWords;
    private $invertedIndex = [];
    public function __construct(string $fileName, string $lang = 'es') {
        $stopwordsRoute = 'public/stopwords/';
        $stopWordFile = $stopwordsRoute . 'stopwords_' . strtoupper($lang) . '.txt';
        $this->stopWords = $this->getStopWordsFileContent($stopWordFile);
        $this->invertedIndex = $this->buildInvertedIndex($fileName);
        arsort($this->invertedIndex);

    }

    public function getInvertedIndex() {
        return $this->invertedIndex;
    }

    // private function maxToMin($a, $b) {
    //     if ($a == $b) return 0;
    //     return ($a > $b) ? -1 : 1;
    // }

    private function buildInvertedIndex($filename) {
        $invertedIndex = [];
     
        // foreach($filenames as $filename) {
        $data = file_get_contents($filename);
    
        if($data === false) die('Unable to read file: ' . $filename);
    
        preg_match_all('/(\w+)/', $data, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $word = strtolower($match[0]);
            if(in_array($word, $this->stopWords) || strlen(preg_replace('/[0-9]+/','',$word)) <= 0) continue;
            if(!array_key_exists($word, $invertedIndex)) {
                $invertedIndex[$word] = [];
                $invertedIndex[$word]['count'] = 1;
            } else { $invertedIndex[$word]['count'] += 1; }
            // if(!in_array($filename, $invertedIndex[$word], true)) {
            //     $invertedIndex[$word][] = $filename;
            // }
        }
        // }
        return $invertedIndex;
    }

    public function lookupWord($word) {
        return array_key_exists($word, $this->invertedIndex) ? $this->invertedIndex[$word] : false;
    }

    private function getStopWordsFileContent($fileName) {
        $content = file_get_contents($fileName);
        if ($content === false) return [];
        return preg_split("/[\s]+/", $content);
    }
}
