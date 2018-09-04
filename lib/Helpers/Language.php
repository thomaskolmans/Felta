<?php
namespace lib\Helpers;

class Language{
        
    public $languagelist = array();
    public $sql;
    public $default = "en";

    public $languages = array("ab" => "Abkhazian",
                            "aa" => "Afar",
                            "af" => "Afrikaans",
                            "ak" => "Akan",
                            "sq" => "Albanian",
                            "am" => "Amharic",
                            "ar" => "Arabic",
                            "an" => "Aragonese",
                            "hy" => "Armenian",
                            "as" => "Assamese",
                            "av" => "Avaric",
                            "ae" => "Avestan",
                            "ay" => "Aymara",
                            "az" => "Azerbaijani",
                            "bm" => "Bambara",
                            "ba" => "Bashkir",
                            "eu" => "Basque",
                            "be" => "Belarusian",
                            "bn" => "Bengali(Bangla)",
                            "bh" => "Bihari",
                            "bi" => "Bislama",
                            "bs" => "Bosnian",
                            "br" => "Breton",
                            "bg" => "Bulgarian",
                            "my" => "Burmese",
                            "ca" => "Catalan",
                            "ch" => "Chamorro",
                            "ce" => "Chechen",
                            "ny" => "Chichewa,Chewa,Nyanja",
                            "zh" => "Chinese",
                            "ns" => "Chinese(Simplified)zh-H",
                            "nt" => "Chinese(Traditional)zh-H",
                            "cv" => "Chuvash",
                            "kw" => "Cornish",
                            "co" => "Corsican",
                            "cr" => "Cree",
                            "hr" => "Croatian",
                            "cs" => "Czech",
                            "da" => "Danish",
                            "dv" => "Divehi,Dhivehi,Maldivian",
                            "nl" => "Dutch",
                            "dz" => "Dzongkha",
                            "en" => "English",
                            "eo" => "Esperanto",
                            "et" => "Estonian",
                            "ee" => "Ewe",
                            "fo" => "Faroese",
                            "fj" => "Fijian",
                            "fi" => "Finnish",
                            "fr" => "French",
                            "ff" => "Fula,Fulah,Pulaar,Pular",
                            "gl" => "Galician",
                            "gd" => "Gaelic(Scottish)",
                            "gv" => "Gaelic(Manx)",
                            "ka" => "Georgian",
                            "de" => "German",
                            "el" => "Greek",
                            "kl" => "Greenlandic",
                            "gn" => "Guarani",
                            "gu" => "Gujarati",
                            "ht" => "HaitianCreole",
                            "ha" => "Hausa",
                            "he" => "Hebrew",
                            "hz" => "Herero",
                            "hi" => "Hindi",
                            "ho" => "HiriMotu",
                            "hu" => "Hungarian",
                            "is" => "Icelandic",
                            "io" => "Ido",
                            "ig" => "Igbo",
                            "in" => "Indonesianid,",
                            "ia" => "Interlingua",
                            "ie" => "Interlingue",
                            "iu" => "Inuktitut",
                            "ik" => "Inupiak",
                            "ga" => "Irish",
                            "it" => "Italian",
                            "ja" => "Japanese",
                            "jv" => "Javanese",
                            "kl" => "Kalaallisut,Greenlandic",
                            "kn" => "Kannada",
                            "kr" => "Kanuri",
                            "ks" => "Kashmiri",
                            "kk" => "Kazakh",
                            "km" => "Khmer",
                            "ki" => "Kikuyu",
                            "rw" => "Kinyarwanda(Rwanda)",
                            "rn" => "Kirundi",
                            "ky" => "Kyrgyz",
                            "kv" => "Komi",
                            "kg" => "Kongo",
                            "ko" => "Korean",
                            "ku" => "Kurdish",
                            "kj" => "Kwanyama",
                            "lo" => "Lao",
                            "la" => "Latin",
                            "lv" => "Latvian(Lettish)",
                            "li" => "Limburgish(Limburger)",
                            "ln" => "Lingala",
                            "lt" => "Lithuanian",
                            "lu" => "Luga-Katanga",
                            "lg" => "Luganda,Ganda",
                            "lb" => "Luxembourgish",
                            "gv" => "Manx",
                            "mk" => "Macedonian",
                            "mg" => "Malagasy",
                            "ms" => "Malay",
                            "ml" => "Malayalam",
                            "mt" => "Maltese",
                            "mi" => "Maori",
                            "mr" => "Marathi",
                            "mh" => "Marshallese",
                            "mo" => "Moldavian",
                            "mn" => "Mongolian",
                            "na" => "Nauru",
                            "nv" => "Navajo",
                            "ng" => "Ndonga",
                            "nd" => "NorthernNdebele",
                            "ne" => "Nepali",
                            "no" => "Norwegian",
                            "nb" => "Norwegianbokmål",
                            "nn" => "Norwegiannynorsk",
                            "ii" => "Nuosu",
                            "oc" => "Occitan",
                            "oj" => "Ojibwe",
                            "cu" => "OldChurchSlavonic,OldBulgarian",
                            "or" => "Oriya",
                            "om" => "Oromo(AfaanOromo)",
                            "os" => "Ossetian",
                            "pi" => "Pāli",
                            "ps" => "Pashto,Pushto",
                            "fa" => "Persian(Farsi)",
                            "pl" => "Polish",
                            "pt" => "Portuguese",
                            "pa" => "Punjabi(Eastern)",
                            "qu" => "Quechua",
                            "rm" => "Romansh",
                            "ro" => "Romanian",
                            "ru" => "Russian",
                            "se" => "Sami",
                            "sm" => "Samoan",
                            "sg" => "Sango",
                            "sa" => "Sanskrit",
                            "sr" => "Serbian",
                            "sh" => "Serbo-Croatian",
                            "st" => "Sesotho",
                            "tn" => "Setswana",
                            "sn" => "Shona",
                            "ii" => "SichuanYi",
                            "sd" => "Sindhi",
                            "si" => "Sinhalese",
                            "ss" => "Siswati",
                            "sk" => "Slovak",
                            "sl" => "Slovenian",
                            "so" => "Somali",
                            "nr" => "SouthernNdebele",
                            "es" => "Spanish",
                            "su" => "Sundanese",
                            "sw" => "Swahili(Kiswahili)",
                            "ss" => "Swati",
                            "sv" => "Swedish",
                            "tl" => "Tagalog",
                            "ty" => "Tahitian",
                            "tg" => "Tajik",
                            "ta" => "Tamil",
                            "tt" => "Tatar",
                            "te" => "Telugu",
                            "th" => "Thai",
                            "bo" => "Tibetan",
                            "ti" => "Tigrinya",
                            "to" => "Tonga",
                            "ts" => "Tsonga",
                            "tr" => "Turkish",
                            "tk" => "Turkmen",
                            "tw" => "Twi",
                            "ug" => "Uyghur",
                            "uk" => "Ukrainian",
                            "ur" => "Urdu",
                            "uz" => "Uzbek",
                            "ve" => "Venda",
                            "vi" => "Vietnamese",
                            "vo" => "Volapük",
                            "wa" => "Wallon",
                            "cy" => "Welsh",
                            "wo" => "Wolof",
                            "fy" => "WesternFrisian",
                            "xh" => "Xhosa",
                            "ji" => "Yiddishyi,",
                            "yo" => "Yoruba",
                            "za" => "Zhuang,Chuang",
                            "zu" => "Zulu"
                            );

    public function __construct($sql){
        $this->sql = $sql;
        $this->default = \lib\Felta::getInstance()->getConfig("default_language");
        $this->createTable();
    }   
    public function addToList($language){
        $this->languagelist[] = $language;
        return $this;
    }
    public function findShort($long){
        return array_search($long, $this->languages);
    }
    public function add($lang){
        $this->addLang($lang,$this->languages[$lang]);
        return $this;
    }
    public function getLanguageList(){
        $langs = $this->sql->select("name","languages",array());
        $this->addToList($this->languages[$this->default]);
        if(!is_array($langs)){
            if($langs !== null){
                $this->addToList($langs);
            }
            return $this->languagelist;
        }
        foreach($langs as $lang){
            $this->addToList($lang["name"]);
        }
        return $this->languagelist;
    }

    public function remove($language){
        $this->removeLang($language);
        return $this;
    }
    public function get($languagelist = []){
        if(empty($languagelist)){
            $this->languagelist = $this->getLanguageList();
        }else{
            $this->languagelist = $languagelist;
        }
        if(isset($_GET["lang"]) && in_array($_GET["lang"], $this->languagelist)){
            return $_GET["lang"];
        }else if(isset($_SESSION["lang"]) && in_array($_SESSION["lang"], $this->languagelist)){
            return $_SESSION["lang"];
        }else if(isset($_COOKIE["lang"]) && in_array($_COOKIE["lang"], $this->languagelist)){
            return $_COOKIE["lang"];
        }
        $l = $this->getPrefered($this->languagelist);
        if(strlen($l) > 2){
            return $this->findShort($l);
        }
        return $l;
    }

    public function getPrefered($available_languages,$http_accept_language="auto"){ 
        if ($http_accept_language == "auto") $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : ''; 
        preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" . 
                       "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i", 
                       $http_accept_language, $hits, PREG_SET_ORDER); 
        $bestlang = $available_languages[0]; 
        $bestqval = 0; 

        foreach ($hits as $arr) { 
            $langprefix = strtolower ($arr[1]); 
            if (!empty($arr[3])) { 
                $langrange = strtolower ($arr[3]); 
                $language = $langprefix . "-" . $langrange; 
            } 
            else $language = $langprefix; 
            $qvalue = 1.0; 
            if (!empty($arr[5])) $qvalue = floatval($arr[5]); 
            if (in_array($language,$available_languages) && ($qvalue > $bestqval)) { 
                $bestlang = $language; 
                $bestqval = $qvalue; 
            } 
            else if (in_array($langprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) { 
                $bestlang = $langprefix; 
                $bestqval = $qvalue*0.9; 
            } 
        } 
        return $bestlang; 
    }
    public function addLang($lang,$name){
        if(!$this->sql->exists("languages",array("lang" => $lang))){
            $this->sql->insert("languages",array(0,$lang,$name));
        }
        return $this;
    }
    public function removeLang($name){
        $this->sql->delete("languages",["name" => $name]);
        return $this;
    }
    public function getDefault(){
        return $this->default;
    }
    public function createTable(){
        if(!$this->sql->exists("languages",array())){
            $this->sql->create("languages",array(
                "id" => "int auto_increment",
                "lang" => "varchar(10)",
                "name" => "varchar(255)"
                ),"id");
        }
        return $this;
    }
}
?>