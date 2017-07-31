</html>
<head>
 <title>Felta | Editor</title>
 <link href="felta/stylesheets/main.css" rel="stylesheet" />
 <link href="felta/js/quill/quill.snow.css" rel="stylesheet" />
 <link href="felta/js/Jcrop/jquery.Jcrop.css" rel="stylesheet" />
 <link rel="stylesheet" href="felta/fonts/font-awesome.min.css" />
 <link rel="stylesheet" href="felta/fonts/font-awesome.css" />
 <link rel="icon" href="felta/images/black.png" type="image/png" />
 <script src="felta/js/jquery-1.11.3.min.js"></script>
 <script src="felta/js/Jcrop/jquery.Jcrop.js"></script>
 <script src="felta/js/quill/quill.min.js"></script>
 <script src="felta/js/edit.js"></script>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta charset="UTF-8">
</head>
<body>
  <include>felta/parts/nav.tpl</include>
   <div class="main">
      <div class="top">
        <h1>Editor</h1>
        <div class="edit_buttons" id="text_edit_buttons">
          <button id="text_edit_cancel">Cancel</button>
          <button id="text_edit_save">Save changes</button>
        </div>
        <div class="edit_buttons" id="image_edit_buttons">
          <button id="image_edit_cancel">Cancel</button>
          <button id="image_edit_save">Save changes</button>
        </div>
        <div class="edit_buttons" id="line_edit_buttons">
          <button id="line_edit_cancel">Cancel</button>
          <button id="line_edit_save">Save changes</button>
        </div>
        <div class="basic" id="basic_buttons">
          <select id="active_language" class="language">
            <?php
              $lang = new lib\Helpers\Language(lib\Felta::getInstance()->sql);
              $langlist = (array) $lang->getLanguageList();
              for($i = 0; $i < sizeof($langlist); $i++){
                $language = $langlist[$i];
                $key = $lang->findShort($language);
                if($i == 0){
                  echo "<option class='active' value='$key'>$language</option>";
                }else{
                  echo "<option value='$key'>$language</option>";
                }
              }
            ?>
          </select>
        </div>
      </div> 
      <div class="wrap">
          <iframe  id="iframe" style=""></iframe>
      </div>
      <section class="editor_text" id="text_editor">
        <div class="background" id="text_editor_background"></div>
        <div class="editor">
          <div class="container" id="texteditor"></div>
        </div>
      </section>
      <section class="editor_line" id="line_editor">
        <div class="background" id="line_editor_background"></div>
        <div class="editor">
            <input type="text" id="line_editor_value"/>
        </div>
      </section>
      <section class="image_editor" id="image_editor">
        <div class="background" id="image_editor_background"></div>
        <div class="editor">
          <div class="container" id="imageeditor">
                <form method="post" class="select-image" enctype="multipart/form-data">
                    <img  id="image" class="imageid" src="#" alt="your image" style="display: none;" />
                    <input type="hidden" name="x1" id="x1">
                    <input type="hidden" name="y1" id="y1">
                    <input type="hidden" name="x2" id="x2">
                    <input type="hidden" name="y2" id="y2">
                    <input type="hidden" name="w"  id="w">
                    <input type="hidden" name="h"  id="h">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="fullwidth" id="fullwidth">
                    <div class="box__input">
                      <svg class="box__icon" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                      <input class="box__file" type="file" id="file" accept="image/*" />
                      <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
                    </div>
                </form>
              </div>
        </div>
      </section>
   </div>
</body>
</html>