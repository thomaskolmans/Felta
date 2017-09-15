</html>
<head>
   <title>Felta | Agenda</title>
   <link href="felta/stylesheets/main.css" rel="stylesheet">
   <link href="felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="Felta/js/datepicker/jquery.datetimepicker.css">
   <link rel="icon" href="Felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="felta/fonts/font-awesome.css" />
   <script src="felta/js/jquery-1.11.3.min.js"></script>
   <script src="felta/js/quill/quill.min.js"></script>
   <script src="felta/js/datepicker/jquery.datetimepicker.min.js" type="text/javascript"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script type="text/javascript">
    $(document).ready(function(){
        var active = document.location.pathname;
        var parts = active.split('/');
        var active_id = "main";
        for(var i = 0; 2 >= i; i++){
          parts.shift();
        }
        for (var i = 0; parts.length - 1>= i; i += 2) {
          var first = parts[i];
          number = i + 1;
          var second = parts[number];
          if(first == "id"){
            active_id = second;
          }else{
            window[first] = second;
          }
        }
        onePage("#"+active_id,null,null);
        var lastactive = "#"+active_id;
        $("a").on("click",function(e){
            if($(this).attr("href")[0] == "#"){
                e.preventDefault();
                var id = $(this).attr("href");
                if(id == "#new"){
                  jQuery('#datetimepicker').datetimepicker({
                    format:'d.m.Y H:i',
                    inline:true,
                    lang:'en',
                    startDate:new Date()
                  });
                }
                onePage("#"+active_id,id,lastactive);
                lastactive = id;       
            }
        });

        var quill = new Quill('#editor', {
          theme: 'snow',
          font: 'Sans Serif',
          modules: {
            toolbar: [[{'header': [0,3,2,1]}],
                      ['bold','italic','underline','strike'],
                      ['blockquote','code-block'],
                      [{'list': 'ordered'},{'list': 'bullet'}],
                      [{ 'align': [] }],
                      ['link','image','video']
                     ]
          }
        });
        var update_quill = new Quill('#update_editor', {
          theme: 'snow',
          font: 'Sans Serif',
          modules: {
            toolbar: [[{'header': [0,3,2,1]}],
                      ['bold','italic','underline','strike'],
                      ['blockquote','code-block'],
                      [{'list': 'ordered'},{'list': 'bullet'}],
                      [{ 'align': [] }],
                      ['link','image','video']
                     ]
          }
        });
        quill.on('text-change',function(delta,text){
          document.getElementById('description').innerHTML = quill.root.innerHTML;
        });
        update_quill.on('text-change',function(delta,text){
          document.getElementById('update_editor_value').innerHTML = update_quill.root.innerHTML;
        });

    });
    function onePage(first,id,lastactive){
        if(lastactive != null){
            $(lastactive).hide();
        }
        if(first)
        if(id != null){
            $(id).fadeIn().css("display","inline-block");
        }else{
            $(first).fadeIn().css("display","inline-block");
        }
        if(first == "#update" || id == "#update"){
            jQuery('#datetimepicker_update').datetimepicker({
              format:'d.m.Y H:i',
              inline:true,
              lang:'en',
              startDate: Date.parse($("#datetimepicker_update").val())
            });
        }
        return;
    }

   </script>
</head>
<body>
  <include>felta/parts/nav.tpl</include>
  <div class="main dashboard container multi-page" id="main">
    <h1> Agenda </h1>
    <a href="#new"><button class="add">Add date</button></a>
    <div class="stats no-margin agenda">
      <table>
      <tr>
        <th>Title</th>
        <th>Location</th>
        <th>Date</th>
        <th class="clear"></th>
        <th class="clear"></th>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php
          $agenda = new lib\Post\Agenda();
          $items = $agenda->getAll();
          if($items != null){
          foreach($items as $item){
            echo "
            <tr>
              <td class='align-left'>{$item['title']}</td>
              <td class='align-left'>{$item['location']}</td>
              <td>{$item['date']}</td>
              <td><a href='/Felta/agenda/id/update/agenda_id/".$item["id"]."'><button>Edit</button></a></td>
              <td><a href='/Felta/agenda/delete/".$item["id"]."'><div class='delete'></div></a></td>
            </tr>
            ";
          }
          }
      ?>
      </table>
    </div>
  </div>
  <div class="main dashboard multi-page" id="new">
    <h1>New agenda item</h1>
    <form method="post" class="agenda">
      <div class="input-group">
        <label>Title</label>
        <input type="text" name="title" placeholder="A boat tour through Amsterdam" />
      </div>
      <div class="input-group">
        <label>Where?</label>
        <input type="text" name="location" placeholder="Amsterdam, The Netherlands">
      </div>
      <div class="input-group">
        <label>When?</label>
        <input type="text" id="datetimepicker" name="date" />
      </div>
      <div class="input-group">
        <label>Description</label>
        <div id="editor"></div>
        <textarea style="display: none" id="description" name="description">
      </div>
      <div class="input-group right">
        <a href="/Felta/agenda"><input type="button" value="Cancel" id="cancel_agenda"></a>
        <input type="submit" name="new_agenda" value="Add item">
      </div>
    </form>
  </div>
  <div class="main dashboard multi-page" id="update">
    <h1>Edit agenda item</h1>
    <?php
      $agenda_id = $_GET["agenda_id"];
      $agenda = new lib\Post\Agenda();
      $agenda_item = $agenda->getById($agenda_id);

      $title = $agenda_item['title'];
      $location = $agenda_item['location'];
      $when = $agenda_item['date'];
      $description = $agenda_item['description'];
    ?>
    <form method="post" class="agenda" action="/felta/agenda/update">
      <?php echo '<input type="text" name="id" value="'.$agenda_id.'" style="display:none" /> '; ?>
      <div class="input-group">
        <label>Title</label>
        <?php echo '<input type="text" name="title" value="'.$title.'" placeholder="A boat tour through Amsterdam" />'; ?>
      </div>
      <div class="input-group">
        <label>Where?</label>
        <?php echo '<input type="text" name="location" value="'.$location.'" placeholder="Amsterdam, The Netherlands">'; ?>
      </div>
      <div class="input-group">
        <label>When?</label>
        <?php echo '<input type="datetime" date="'.$when.'" value="'.$when.'" id="datetimepicker_update" name="date" />'; ?>
      </div>
      <div class="input-group">
        <label>Description</label>
        <?php echo '<div id="update_editor">'.$description.'</div>'; ?>
        <?php echo '<textarea style="display: none" id="update_editor_value" value="'.$description.'" name="description"></textarea>'; ?>
      </div>
      <div class="input-group right">
        <a href="/Felta/agenda"><input type="button" value="Cancel" id="cancel_agenda"></a>
        <input type="submit" name="new_agenda" value="Update item">
      </div>
    </form>
  </div>
</body>
</html>