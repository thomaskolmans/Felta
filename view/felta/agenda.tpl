<html>
<head>
   <title>Felta | Agenda</title>
   <link href="/felta/stylesheets/main.css" rel="stylesheet">
   <link href="/felta/js/quill/quill.snow.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="/felta/js/datepicker/jquery.datetimepicker.css">
   <link rel="icon" href="/felta/images/black.png" type="image/png" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.min.css" />
   <link rel="stylesheet" href="/felta/fonts/font-awesome.css" />
   <script src="/felta/js/jquery-1.11.3.min.js"></script>
   <script src="/felta/js/quill/quill.min.js"></script>
   <script src="/felta/js/datepicker/jquery.datetimepicker.min.js" type="text/javascript"></script>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script type="text/javascript">
    $(document).ready(function(){
        window.times = [
          '00:00', '00:15', '00:30','00:45','01:00',
          '01:15', '01:30','01:45','02:00',
          '02:15', '02:30','02:45','03:00',
          '03:15', '03:30','03:45','04:00',
          '04:15', '04:30','04:45','05:00',
          '05:15', '05:30','05:45','06:00',
          '06:15', '06:30','06:45','07:00',
          '07:15', '07:30','07:45','08:00',
          '08:15', '08:30','08:45','09:00',
          '09:15', '09:30','09:45','10:00',
          '10:15', '10:30','10:45','11:00',
          '11:15', '11:30','11:45',
          '12:00', '12:15', '12:30','12:45','13:00',
          '13:15', '13:30','13:45','14:00',
          '14:15', '14:30','14:45','15:00',
          '15:15', '15:30','15:45','16:00',
          '16:15', '16:30','16:45','17:00',
          '17:15', '17:30','17:45','18:00',
          '18:15', '18:30','18:45','19:00',
          '19:15', '19:30','19:45','20:00',
          '20:15', '20:30','20:45','21:00',
          '21:15', '21:30','21:45','22:00',
          '22:15', '22:30','22:45','23:00',
          '23:15', '23:30','23:45'
        ];

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
                  jQuery('#datetimepicker_from').datetimepicker({
                    format:'d.m.Y H:i',
                    inline:true,
                    lang:'en',
                    startDate:new Date(),
                    allowTimes:times
                  });
                  jQuery('#datetimepicker_until').datetimepicker({
                    format:'d.m.Y H:i',
                    inline:true,
                    lang:'en',
                    startDate:new Date(),
                    allowTimes:times
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
            jQuery('#datetimepicker_update_from').datetimepicker({
              format:'d.m.Y H:i',
              inline:true,
              lang:'en',
              startDate: Date.parse($("#datetimepicker_update_from").val()),
              allowTimes:times
            });
            jQuery('#datetimepicker_update_until').datetimepicker({
              format:'d.m.Y H:i',
              inline:true,
              lang:'en',
              startDate: Date.parse($("#datetimepicker_update_until").val()),
              allowTimes:times
            });
        }
        return;
    }

   </script>
</head>
<body>
  <include>felta/parts/nav.tpl</include>
  <div class="main-wrapper">
    <div class="main dashboard container multi-page" id="main">
      <h1> Agenda </h1>
      <div class="stats no-margin agenda relative">
        <a href="#new"><button class="add">Add date</button></a>
        <table>
        <tr>
          <th>Title</th>
          <th>Location</th>
          <th>From</th>
          <th>Until</th>
          <th class="clear"></th>
          <th class="clear"></th>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php
            $agenda = new lib\post\Agenda();
            $items = $agenda->getAll();
            if ($items != null) {
              foreach($items as $item) {
                echo "
                <tr>
                  <td class='align-left'>{$item['title']}</td>
                  <td class='align-left'>{$item['location']}</td>
                  <td>{$item['from']}</td>
                  <td>{$item['until']}</td>
                  <td><a href='/felta/agenda/id/update/agenda_id/".$item["id"]."'><button>Edit</button></a></td>
                  <td><a href='/felta/agenda/delete/".$item["id"]."'><div class='delete'></div></a></td>
                </tr>
                ";
              }
            } else {
              echo "<tr> <td colspan='7'><i>No agenda items</i></td></tr>";
            }
        ?>
        </table>
      </div>
    </div>
    <div class="main dashboard multi-page" id="new">
      <h1>New agenda item</h1>
      <form method="post" class="full">
        <div class="input-group">
          <label>Title</label>
          <input type="text" name="title" placeholder="A boat tour through Amsterdam" />
        </div>
        <div class="input-group">
          <label>Location</label>
          <input type="text" name="location" placeholder="Amsterdam, The Netherlands">
        </div>
        <div class="input-group">
          <label>From</label>
          <input type="text" id="datetimepicker_from" name="date" />
        </div>
        <div class="input-group">
          <label>Until</label>
          <input type="text" id="datetimepicker_until" name="date" />
        </div>
        <div class="input-group">
          <label>Description</label>
          <div id="editor"></div>
          <textarea style="display: none" id="description" name="description">
        </div>
        <div class="input-group right">
          <a href="/felta/agenda"><input type="button" value="Cancel" id="cancel_agenda"></a>
          <input type="submit" name="new_agenda" value="Add item">
        </div>
      </form>
    </div>
    <div class="main dashboard multi-page" id="update">
      <h1>Edit agenda item</h1>
      <?php
        $agenda_id = $_GET["agenda_id"];
        $agenda = new lib\post\Agenda();
        $agenda_item = $agenda->getById($agenda_id);

        $title = $agenda_item['title'];
        $location = $agenda_item['location'];
        $from = $agenda_item['from'];
        $until = $agenda_item['until'];
        $description = $agenda_item['description'];
      ?>
      <form method="post" class="full" action="/felta/agenda/update">
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
          <label>From?</label>
          <?php echo '<input type="datetime" date="'.$from.'" value="'.$from.'" id="datetimepicker_update_from" name="from" />'; ?>
        </div>
        <div class="input-group">
          <label>Until?</label>
          <?php echo '<input type="datetime" date="'.$until.'" value="'.$until.'" id="datetimepicker_update_until" name="until" />'; ?>
        </div>
        <div class="input-group">
          <label>Description</label>
          <?php echo '<div id="update_editor">'.$description.'</div>'; ?>
          <?php echo '<textarea style="display: none" id="update_editor_value" name="description">'.$description.'</textarea>'; ?>
        </div>
        <div class="input-group right">
          <a href="/felta/agenda"><input type="button" value="Cancel" id="cancel_agenda"></a>
          <input type="submit" name="new_agenda" value="Update item">
        </div>
      </form>
    </div>
  </div>
</body>
</html>