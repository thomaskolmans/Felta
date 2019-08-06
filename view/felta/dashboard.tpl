</html>

<head>
    <title>Felta | Dashboard</title>
    <link href="/felta/stylesheets/main.css" rel="stylesheet">
    <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
    <link rel="icon" href="/felta/images/black.png" type="image/png" />
    <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
    <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
    <script src="/felta/js/jquery-1.11.3.min.js"></script>
    <script src="/felta/js/moment.js" type="text/javascript"></script>
    <script src="/felta/js/Chart.min.js"></script>
    <script src="/felta/js/dashboard.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <include>felta/parts/nav.tpl</include>
    <div class="main-wrapper">
        <div class="main dashboard">
            <h1>Dashboard</h1>
            <div class='flex'>
                <section class="hub half">
                    <h1>Welcome to Felta!</h1>
                    <h2>The easiest content management system</h2>
                    <hr>
                    <div class="live">
                        <div class="label">Website live to the public</div>
                        <label class="switch">
                            <input type="checkbox" class="switcher" checked>
                            <div class="slider round"></div>
                        </label>
                    </div>
                    <div class="status offline" id="website_is">Your website is
                        <status id="website_status">offline</status>
                    </div>
                </section>
                <section class="languages half">
                    <h1> Languages </h1>
                    <?php
                $lang = new lib\Helpers\Language(lib\Felta::getInstance()->sql);
                $langlist = (array) $lang->getLanguageList();
                $i = 0;
                foreach($langlist as $language){ 
                        if($i == 0){
                            echo "<div class='lang_container'><div class='language'>{$language}</div></div>";
                        }else{
                            echo "<div class='lang_container'><div class='language'>{$language}<div class='remove' lang='{$language}'></div></div></div>";
                        }
                        $i++;
                }
            ?>
                        <form method="post" class="new-language">
                            <div class="select-box">
                                <select name="language">
                                    <option disabled selected value>-- select language --</option>
                                    <option value="ab">Abkhazian</option>
                                    <option value="aa">Afar </option>
                                    <option value="af">Afrikaans</option>
                                    <option value="ak">Akan </option>
                                    <option value="sq">Albanian </option>
                                    <option value="am">Amharic</option>
                                    <option value="ar">Arabic </option>
                                    <option value="an">Aragonese</option>
                                    <option value="hy">Armenian </option>
                                    <option value="as">Assamese </option>
                                    <option value="av">Avaric </option>
                                    <option value="ae">Avestan</option>
                                    <option value="ay">Aymara </option>
                                    <option value="az">Azerbaijani</option>
                                    <option value="bm">Bambara</option>
                                    <option value="ba">Bashkir</option>
                                    <option value="eu">Basque </option>
                                    <option value="be">Belarusian </option>
                                    <option value="bn">Bengali (Bangla) </option>
                                    <option value="bh">Bihari </option>
                                    <option value="bi">Bislama</option>
                                    <option value="bs">Bosnian</option>
                                    <option value="br">Breton </option>
                                    <option value="bg">Bulgarian</option>
                                    <option value="my">Burmese</option>
                                    <option value="ca">Catalan</option>
                                    <option value="ch">Chamorro </option>
                                    <option value="ce">Chechen</option>
                                    <option value="ny">Chichewa, Chewa, Nyanja</option>
                                    <option value="zh">Chinese</option>
                                    <option value="ns">Chinese (Simplified) zh-H</option>
                                    <option value="nt">Chinese (Traditional) zh-H</option>
                                    <option value="cv">Chuvash</option>
                                    <option value="kw">Cornish</option>
                                    <option value="co">Corsican </option>
                                    <option value="cr">Cree </option>
                                    <option value="hr">Croatian </option>
                                    <option value="cs">Czech</option>
                                    <option value="da">Danish </option>
                                    <option value="dv">Divehi, Dhivehi, Maldivian </option>
                                    <option value="nl">Dutch</option>
                                    <option value="dz">Dzongkha </option>
                                    <option value="en">English</option>
                                    <option value="eo">Esperanto</option>
                                    <option value="et">Estonian </option>
                                    <option value="ee">Ewe</option>
                                    <option value="fo">Faroese</option>
                                    <option value="fj">Fijian </option>
                                    <option value="fi">Finnish</option>
                                    <option value="fr">French </option>
                                    <option value="ff">Fula, Fulah, Pulaar, Pular </option>
                                    <option value="gl">Galician </option>
                                    <option value="gd">Gaelic (Scottish)</option>
                                    <option value="gv">Gaelic (Manx)</option>
                                    <option value="ka">Georgian </option>
                                    <option value="de">German </option>
                                    <option value="el">Greek</option>
                                    <option value="kl">Greenlandic</option>
                                    <option value="gn">Guarani</option>
                                    <option value="gu">Gujarati </option>
                                    <option value="ht">Haitian Creole </option>
                                    <option value="ha">Hausa</option>
                                    <option value="he">Hebrew </option>
                                    <option value="hz">Herero </option>
                                    <option value="hi">Hindi</option>
                                    <option value="ho">Hiri Motu</option>
                                    <option value="hu">Hungarian</option>
                                    <option value="is">Icelandic</option>
                                    <option value="io">Ido</option>
                                    <option value="ig">Igbo </option>
                                    <option value="in">Indonesian id,</option>
                                    <option value="ia">Interlingua</option>
                                    <option value="ie">Interlingue</option>
                                    <option value="iu">Inuktitut</option>
                                    <option value="ik">Inupiak</option>
                                    <option value="ga">Irish</option>
                                    <option value="it">Italian</option>
                                    <option value="ja">Japanese </option>
                                    <option value="jv">Javanese </option>
                                    <option value="kl">Kalaallisut, Greenlandic </option>
                                    <option value="kn">Kannada</option>
                                    <option value="kr">Kanuri </option>
                                    <option value="ks">Kashmiri </option>
                                    <option value="kk">Kazakh </option>
                                    <option value="km">Khmer</option>
                                    <option value="ki">Kikuyu </option>
                                    <option value="rw">Kinyarwanda (Rwanda) </option>
                                    <option value="rn">Kirundi</option>
                                    <option value="ky">Kyrgyz </option>
                                    <option value="kv">Komi </option>
                                    <option value="kg">Kongo</option>
                                    <option value="ko">Korean </option>
                                    <option value="ku">Kurdish</option>
                                    <option value="kj">Kwanyama </option>
                                    <option value="lo">Lao</option>
                                    <option value="la">Latin</option>
                                    <option value="lv">Latvian (Lettish)</option>
                                    <option value="li">Limburgish ( Limburger)</option>
                                    <option value="ln">Lingala</option>
                                    <option value="lt">Lithuanian </option>
                                    <option value="lu">Luga-Katanga </option>
                                    <option value="lg">Luganda, Ganda </option>
                                    <option value="lb">Luxembourgish</option>
                                    <option value="gv">Manx </option>
                                    <option value="mk">Macedonian </option>
                                    <option value="mg">Malagasy </option>
                                    <option value="ms">Malay</option>
                                    <option value="ml">Malayalam</option>
                                    <option value="mt">Maltese</option>
                                    <option value="mi">Maori</option>
                                    <option value="mr">Marathi</option>
                                    <option value="mh">Marshallese</option>
                                    <option value="mo">Moldavian</option>
                                    <option value="mn">Mongolian</option>
                                    <option value="na">Nauru</option>
                                    <option value="nv">Navajo </option>
                                    <option value="ng">Ndonga </option>
                                    <option value="nd">Northern Ndebele </option>
                                    <option value="ne">Nepali </option>
                                    <option value="no">Norwegian</option>
                                    <option value="nb">Norwegian bokmål </option>
                                    <option value="nn">Norwegian nynorsk</option>
                                    <option value="ii">Nuosu</option>
                                    <option value="oc">Occitan</option>
                                    <option value="oj">Ojibwe </option>
                                    <option value="cu">Old Church Slavonic, Old Bulgarian </option>
                                    <option value="or">Oriya</option>
                                    <option value="om">Oromo (Afaan Oromo)</option>
                                    <option value="os">Ossetian </option>
                                    <option value="pi">Pāli </option>
                                    <option value="ps">Pashto, Pushto </option>
                                    <option value="fa">Persian (Farsi)</option>
                                    <option value="pl">Polish </option>
                                    <option value="pt">Portuguese </option>
                                    <option value="pa">Punjabi (Eastern)</option>
                                    <option value="qu">Quechua</option>
                                    <option value="rm">Romansh</option>
                                    <option value="ro">Romanian </option>
                                    <option value="ru">Russian</option>
                                    <option value="se">Sami </option>
                                    <option value="sm">Samoan </option>
                                    <option value="sg">Sango</option>
                                    <option value="sa">Sanskrit </option>
                                    <option value="sr">Serbian</option>
                                    <option value="sh">Serbo-Croatian </option>
                                    <option value="st">Sesotho</option>
                                    <option value="tn">Setswana </option>
                                    <option value="sn">Shona</option>
                                    <option value="ii">Sichuan Yi </option>
                                    <option value="sd">Sindhi </option>
                                    <option value="si">Sinhalese</option>
                                    <option value="ss">Siswati</option>
                                    <option value="sk">Slovak </option>
                                    <option value="sl">Slovenian</option>
                                    <option value="so">Somali </option>
                                    <option value="nr">Southern Ndebele </option>
                                    <option value="es">Spanish</option>
                                    <option value="su">Sundanese</option>
                                    <option value="sw">Swahili (Kiswahili)</option>
                                    <option value="ss">Swati</option>
                                    <option value="sv">Swedish</option>
                                    <option value="tl">Tagalog</option>
                                    <option value="ty">Tahitian </option>
                                    <option value="tg">Tajik</option>
                                    <option value="ta">Tamil</option>
                                    <option value="tt">Tatar</option>
                                    <option value="te">Telugu </option>
                                    <option value="th">Thai </option>
                                    <option value="bo">Tibetan</option>
                                    <option value="ti">Tigrinya </option>
                                    <option value="to">Tonga</option>
                                    <option value="ts">Tsonga </option>
                                    <option value="tr">Turkish</option>
                                    <option value="tk">Turkmen</option>
                                    <option value="tw">Twi</option>
                                    <option value="ug">Uyghur </option>
                                    <option value="uk">Ukrainian</option>
                                    <option value="ur">Urdu </option>
                                    <option value="uz">Uzbek</option>
                                    <option value="ve">Venda</option>
                                    <option value="vi">Vietnamese </option>
                                    <option value="vo">Volapük</option>
                                    <option value="wa">Wallon </option>
                                    <option value="cy">Welsh</option>
                                    <option value="wo">Wolof</option>
                                    <option value="fy">Western Frisian</option>
                                    <option value="xh">Xhosa</option>
                                    <option value="ji">Yiddish yi,</option>
                                    <option value="yo">Yoruba </option>
                                    <option value="za">Zhuang, Chuang </option>
                                    <option value="ab">Zulu zuAbkhazian</option>
                                    <option value="aa">Afar </option>
                                    <option value="af">Afrikaans</option>
                                    <option value="ak">Akan </option>
                                    <option value="sq">Albanian </option>
                                    <option value="am">Amharic</option>
                                    <option value="ar">Arabic </option>
                                    <option value="an">Aragonese</option>
                                    <option value="hy">Armenian </option>
                                    <option value="as">Assamese </option>
                                    <option value="av">Avaric </option>
                                    <option value="ae">Avestan</option>
                                    <option value="ay">Aymara </option>
                                    <option value="az">Azerbaijani</option>
                                    <option value="bm">Bambara</option>
                                    <option value="ba">Bashkir</option>
                                    <option value="eu">Basque </option>
                                    <option value="be">Belarusian </option>
                                    <option value="bn">Bengali (Bangla) </option>
                                    <option value="bh">Bihari </option>
                                    <option value="bi">Bislama</option>
                                    <option value="bs">Bosnian</option>
                                    <option value="br">Breton </option>
                                    <option value="bg">Bulgarian</option>
                                    <option value="my">Burmese</option>
                                    <option value="ca">Catalan</option>
                                    <option value="ch">Chamorro </option>
                                    <option value="ce">Chechen</option>
                                    <option value="ny">Chichewa, Chewa, Nyanja</option>
                                    <option value="zh">Chinese</option>
                                    <option value="ns">Chinese (Simplified) zh-H</option>
                                    <option value="nt">Chinese (Traditional) zh-H</option>
                                    <option value="cv">Chuvash</option>
                                    <option value="kw">Cornish</option>
                                    <option value="co">Corsican </option>
                                    <option value="cr">Cree </option>
                                    <option value="hr">Croatian </option>
                                    <option value="cs">Czech</option>
                                    <option value="da">Danish </option>
                                    <option value="dv">Divehi, Dhivehi, Maldivian </option>
                                    <option value="nl">Dutch</option>
                                    <option value="dz">Dzongkha </option>
                                    <option value="en">English</option>
                                    <option value="eo">Esperanto</option>
                                    <option value="et">Estonian </option>
                                    <option value="ee">Ewe</option>
                                    <option value="fo">Faroese</option>
                                    <option value="fj">Fijian </option>
                                    <option value="fi">Finnish</option>
                                    <option value="fr">French </option>
                                    <option value="ff">Fula, Fulah, Pulaar, Pular </option>
                                    <option value="gl">Galician </option>
                                    <option value="gd">Gaelic (Scottish)</option>
                                    <option value="gv">Gaelic (Manx)</option>
                                    <option value="ka">Georgian </option>
                                    <option value="de">German </option>
                                    <option value="el">Greek</option>
                                    <option value="kl">Greenlandic</option>
                                    <option value="gn">Guarani</option>
                                    <option value="gu">Gujarati </option>
                                    <option value="ht">Haitian Creole </option>
                                    <option value="ha">Hausa</option>
                                    <option value="he">Hebrew </option>
                                    <option value="hz">Herero </option>
                                    <option value="hi">Hindi</option>
                                    <option value="ho">Hiri Motu</option>
                                    <option value="hu">Hungarian</option>
                                    <option value="is">Icelandic</option>
                                    <option value="io">Ido</option>
                                    <option value="ig">Igbo </option>
                                    <option value="in">Indonesia</option>
                                    <option value="ia">Interlingua</option>
                                    <option value="ie">Interlingue</option>
                                    <option value="iu">Inuktitut</option>
                                    <option value="ik">Inupiak</option>
                                    <option value="ga">Irish</option>
                                    <option value="it">Italian</option>
                                    <option value="ja">Japanese </option>
                                    <option value="jv">Javanese </option>
                                    <option value="kl">Kalaallisut, Greenlandic </option>
                                    <option value="kn">Kannada</option>
                                    <option value="kr">Kanuri </option>
                                    <option value="ks">Kashmiri </option>
                                    <option value="kk">Kazakh </option>
                                    <option value="km">Khmer</option>
                                    <option value="ki">Kikuyu </option>
                                    <option value="rw">Kinyarwanda (Rwanda) </option>
                                    <option value="rn">Kirundi</option>
                                    <option value="ky">Kyrgyz </option>
                                    <option value="kv">Komi </option>
                                    <option value="kg">Kongo</option>
                                    <option value="ko">Korean </option>
                                    <option value="ku">Kurdish</option>
                                    <option value="kj">Kwanyama </option>
                                    <option value="lo">Lao</option>
                                    <option value="la">Latin</option>
                                    <option value="lv">Latvian (Lettish)</option>
                                    <option value="li">Limburgish ( Limburger)</option>
                                    <option value="ln">Lingala</option>
                                    <option value="lt">Lithuanian </option>
                                    <option value="lu">Luga-Katanga </option>
                                    <option value="lg">Luganda, Ganda </option>
                                    <option value="lb">Luxembourgish</option>
                                    <option value="gv">Manx </option>
                                    <option value="mk">Macedonian </option>
                                    <option value="mg">Malagasy </option>
                                    <option value="ms">Malay</option>
                                    <option value="ml">Malayalam</option>
                                    <option value="mt">Maltese</option>
                                    <option value="mi">Maori</option>
                                    <option value="mr">Marathi</option>
                                    <option value="mh">Marshallese</option>
                                    <option value="mo">Moldavian</option>
                                    <option value="mn">Mongolian</option>
                                    <option value="na">Nauru</option>
                                    <option value="nv">Navajo </option>
                                    <option value="ng">Ndonga </option>
                                    <option value="nd">Northern Ndebele </option>
                                    <option value="ne">Nepali </option>
                                    <option value="no">Norwegian</option>
                                    <option value="nb">Norwegian bokmål </option>
                                    <option value="nn">Norwegian nynorsk</option>
                                    <option value="ii">Nuosu</option>
                                    <option value="oc">Occitan</option>
                                    <option value="oj">Ojibwe </option>
                                    <option value="cu">Old Church Slavonic, Old Bulgarian </option>
                                    <option value="or">Oriya</option>
                                    <option value="om">Oromo (Afaan Oromo)</option>
                                    <option value="os">Ossetian </option>
                                    <option value="pi">Pāli </option>
                                    <option value="ps">Pashto, Pushto </option>
                                    <option value="fa">Persian (Farsi)</option>
                                    <option value="pl">Polish </option>
                                    <option value="pt">Portuguese </option>
                                    <option value="pa">Punjabi (Eastern)</option>
                                    <option value="qu">Quechua</option>
                                    <option value="rm">Romansh</option>
                                    <option value="ro">Romanian </option>
                                    <option value="ru">Russian</option>
                                    <option value="se">Sami </option>
                                    <option value="sm">Samoan </option>
                                    <option value="sg">Sango</option>
                                    <option value="sa">Sanskrit </option>
                                    <option value="sr">Serbian</option>
                                    <option value="sh">Serbo-Croatian </option>
                                    <option value="st">Sesotho</option>
                                    <option value="tn">Setswana </option>
                                    <option value="sn">Shona</option>
                                    <option value="ii">Sichuan Yi </option>
                                    <option value="sd">Sindhi </option>
                                    <option value="si">Sinhalese</option>
                                    <option value="ss">Siswati</option>
                                    <option value="sk">Slovak </option>
                                    <option value="sl">Slovenian</option>
                                    <option value="so">Somali </option>
                                    <option value="nr">Southern Ndebele </option>
                                    <option value="es">Spanish</option>
                                    <option value="su">Sundanese</option>
                                    <option value="sw">Swahili (Kiswahili)</option>
                                    <option value="ss">Swati</option>
                                    <option value="sv">Swedish</option>
                                    <option value="tl">Tagalog</option>
                                    <option value="ty">Tahitian </option>
                                    <option value="tg">Tajik</option>
                                    <option value="ta">Tamil</option>
                                    <option value="tt">Tatar</option>
                                    <option value="te">Telugu </option>
                                    <option value="th">Thai </option>
                                    <option value="bo">Tibetan</option>
                                    <option value="ti">Tigrinya </option>
                                    <option value="to">Tonga</option>
                                    <option value="ts">Tsonga </option>
                                    <option value="tr">Turkish</option>
                                    <option value="tk">Turkmen</option>
                                    <option value="tw">Twi</option>
                                    <option value="ug">Uyghur </option>
                                    <option value="uk">Ukrainian</option>
                                    <option value="ur">Urdu</option>
                                    <option value="uz">Uzbek</option>
                                    <option value="ve">Venda</option>
                                    <option value="vi">Vietnamese </option>
                                    <option value="vo">Volapük</option>
                                    <option value="wa">Wallon </option>
                                    <option value="cy">Welsh</option>
                                    <option value="wo">Wolof</option>
                                    <option value="fy">Western Frisian</option>
                                    <option value="xh">Xhosa</option>
                                    <option value="ji">Yiddish</option>
                                    <option value="yo">Yoruba</option>
                                    <option value="za">Zhuang, Chuang</option>
                                    <option value="zu">Zulu</option>
                                </select>
                            </div>
                            <input type="submit" value="add" name="addlanguage">
                            <?php
                if(isset($_POST["addlanguage"]) && isset($_POST["language"])){
                        $language = $_POST["language"];
                        $lang->createTable();
                        $lang->add($language);
                        header("Location: /felta/dashboard");
                }
                ?>
                        </form>
                        <div class="new" id="new_language"></div>
                </section>
                <section class="full stats">
                    <h1> Statistics </h1>
                    <canvas id="visitors"></canvas>
                    <table>
                        <tr>
                            <th class="clean"></th>
                            <td>Day</td>
                            <td>Week</td>
                            <td>Month</td>
                            <td>Year</td>
                        </tr>
                        <tr>
                            <?php
                        $sql = lib\Felta::getInstance()->sql;
                ?>
                                <th>Unique users</th>
                                <td>
                                    <?php echo $sql->execute("select count(*) from visitors_unique where date >= curdate()")[0][0]; ?>
                                </td>
                                <td>
                                    <?php echo $sql->execute("select count(*) from visitors_unique where yearweek(date) = yearweek(curdate())")[0][0]; ?>
                                </td>
                                <td>
                                    <?php echo $sql->execute("select count(*) from visitors_unique where year(date) = year(curdate()) and month(date) = month(curdate())")[0][0]; ?>
                                </td>
                                <td>
                                    <?php echo $sql->execute("select count(*) from visitors_unique where year(date) = year(curdate())")[0][0]; ?>
                                </td>
                        </tr>
                        <tr>
                            <th>Sessions </th>
                            <td>
                                <?php echo $sql->execute("select count(*) from visitors_total where date >= curdate()")[0][0]; ?>
                            </td>
                            <td>
                                <?php echo $sql->execute("select count(*) from visitors_total where yearweek(date) = yearweek(curdate())")[0][0]; ?>
                            </td>
                            <td>
                                <?php echo $sql->execute("select count(*) from visitors_total where year(date) = year(curdate()) and month(date) = month(curdate())")[0][0]; ?>
                            </td>
                            <td>
                                <?php echo $sql->execute("select count(*) from visitors_total where year(date) = year(curdate())")[0][0]; ?>
                            </td>
                        </tr>
                    </table>
                </section>
            </div>
            <div class="thankyou">
                Thank you for using <b>Felta!</b>
            </div>
        </div>
    </div>
</body>

</html>