</html>
<head>
   <title>Felta | Blog</title>
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
        var active = document.location.pathname;
        var parts = active.split('/');
        var active_id = "main";
        
        if (active.indexOf("new") !== -1) {
            active_id = "new";
        } else if (active.indexOf("update") !== -1) {
            active_id = "update";
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
        
        $("#add-attribute" + (i + 1)).on("click", function(e) {
          e.preventDefault();
          var attributeId = parseInt(e.target.getAttribute("id").replace("add-attribute", ""));
          var cVariants = attributeId -1;
          var parent = $('#attributes' + attributeId);
          var template = $("#attributes" + attributeId + " #attribute-template")[0];
          var attributeCount = $(parent).find(".attribute").length - 1
          var clone = template.cloneNode(true);
      
          clone.setAttribute("class", "attribute");
          clone.setAttribute("id", "");
          $(clone).find(".attribute-name")[0]
            .setAttribute("name", "variants[" + (cVariants) + "][attributes][" + attributeCount + "][name]");
          $(clone).find(".attribute-value")[0]
            .setAttribute("name", "variants[" + (cVariants) + "][attributes][" + attributeCount + "][value]");
          $(clone).find("#delete").on("click", function(){
            clone.remove();
          });
      
          parent.prepend(clone);
        });

    });

    function onePage(first, id, lastactive){
        if(lastactive != null){
            $(lastactive).hide();
        }
        if(first)
        if(id != null){
            $(id).fadeIn().css("display","inline-block");
        }else{
            $(first).fadeIn().css("display","inline-block");
        }
        if(first === "#update" || id === "#update"){
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
    <?php
        use lib\post\blog\Blog;
    ?>
    <include>Felta/parts/nav.tpl</include>
    <div class="main-wrapper">
        <div class="main dashboard container multi-page" id="main">
            <h1>FAQ</h1>
            <div class="stats no-margin news relative">
            <a href="#new"><button class="add">Add Q&A</button></a>
            <table>
            <tr>
                <th>Name</th>
                <th>updatedAt</th>
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
                $items = Blog::getAll();
                if($items != null && count($items) > 0){
                    foreach($items as $item){
                        echo "
                            <tr>
                                <td><a href='/felta/blog/".$item->getId()."/article'>".$item->getName()."</a></td>
                                <td><a href='/felta/blog/".$item->getId()."/article'>".$item->getUpdatedAt()->format("d-m-Y H:m")."</a></td>
                                <td><a href='/felta/blog/".$item->getId()."/update'><button>Edit</button></a></td>
                                <td onclick='return (function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        url: \"/felta/blog/delete/".$item->getId()."\",
                                        type: \"DELETE\",
                                        success: function(result) {
                                            window.location.reload();
                                        }
                                    });
                                })(event)'><div class='delete'></div></td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr> <td colspan='7'><i>No blogs</i></td></tr>";
                }
            ?>
            </table>
            </div>
        </div>
        <div class="main dashboard multi-page" id="new">
            <h1>New Q&A</h1>
            <form method="post" class="full" onsubmit="return (function(e){
                e.preventDefault();
                $.ajax({
                    url: '/felta/faq',
                    type: 'POST',
                    data: $('#new form').serialize(),
                    success: function(result) {
                        window.location.reload();
                    }
                });
            })(event)">
                <div class="input-group">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="Title..." />
                </div>
                <div class="input-group">
                  <label>Questions</label>
                  <div class="attributes" id="attributes">
                    <div class="attribute template" id="attribute-template">
                      <input type="text" class="attribute-name" placeholder="Name"/>
                      <input type="text" class="attribute-value" placeholder="Value"/>
                      <button class="delete" id="delete"></button>
                    </div>
                    <button class="" id="add-attribute">Add attribute</button>
                  </div>  
                </div>
                <div class="input-group right">
                    <a href="/felta/faq"><input type="button" value="Cancel"></a>
                    <input type="submit" value="Add faq">
                </div>
            </form>
        </div>
        <div class="main dashboard multi-page" id="update">
            <h1>Edit blog item</h1>
            <?php
                $id = $_GET["id"];
                $blog = Blog::get($id);

                $name = $blog->getName();
                $description = $blog->getDescription();
            ?>
            <form method="post" class="full" onsubmit="return (function(e){
                    e.preventDefault();
                    $.ajax({
                        url: '/felta/blog',
                        type: 'PUT',
                        data: $('#update form').serialize(),
                        success: function(result) {
                            window.location.replace('/felta/blog');
                        }
                    });
                })(event)">
                <?php echo '<input type="text" name="id" value="'.$id.'" style="display:none" /> '; ?>
                <div class="input-group">
                    <label>Name</label>
                    <?php echo '<input type="text" name="name" value="'.$name.'" placeholder="A boat tour through Amsterdam" />'; ?>
                </div>
                <div class="input-group">
                    <label>Description</label>
                    <?php echo '<div id="update_editor">'.$description.'</div>'; ?>
                    <?php echo '<textarea style="display: none" id="update_editor_value"  name="description">'.$description.'</textarea>'; ?>
                </div>
                <div class="input-group right">
                    <a href="/felta/blog"><input type="button" value="Cancel" ></a>
                    <input type="submit" value="Update blog">
                </div>
            </form>
        </div>
    </div>
</body>
</html>