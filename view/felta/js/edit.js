var www;
var hhh;
var quill;
var id;

var language;

var imageCords = {
    x1: 0,
    y1: 0,
    x2: 0,
    y2: 0,
    w: 0,
    h: 0
};

$(document).ready(function(){
    var domain = getDomain();
    document.domain = domain;
    $iframe = $("#iframe");
    $iframe.attr('src','http://' + domain +'/lang/'+$("#active_language").val());
    $iframe.on("load",function(){
        editor();
    });
    quill = new Quill('#texteditor', {
      theme: 'snow',
      font: 'Sans Serif',
      modules: {
        toolbar: [
          [
            {
              'header': [0,3,2,1]
            }
          ],
          [
            'bold','italic','underline','strike'
          ],
          [
            'blockquote','code-block'
          ],
          [
            {
              'list': 'ordered'
            },
            {
              'list': 'bullet'
            }
          ],
          [
            {
              'align': [] 
            }
          ],
          [
            'link','image','video'
          ]
        ]
      }
    });

    /* Close text editor */
    $("#text_edit_cancel, #text_editor_background").click(function(){
      closeTextEditor();
    });
    /* Close image editor */
    $("#image_edit_cancel, #image_editor_background").click(function(){
      closeImageEditor();
    });
    /* Close line editor */
    $("#line_edit_cancel, #line_editor_background").on("click",function(){
      closeLineEditor();
    });
    $("#link_edit_cancel, #link_edit_background").on("click",function(){
      closeLinkEditor();
    });
    var isAdvancedUpload = function() {
          var div = document.createElement('div');
          return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();
    var $form = $('.select-image');
    if (isAdvancedUpload) {
      $form.addClass('has-advanced-upload');
    }
    if (isAdvancedUpload) {
      var droppedFiles = false;
      $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
      })
      .on('dragover dragenter', function() {
        $form.addClass('is-dragover');
      })
      .on('dragleave dragend drop', function() {
        $form.removeClass('is-dragover');
      })
      .on('drop', function(e) {
        droppedFiles = e.originalEvent.dataTransfer.files;
        var ajaxData = new FormData($form.get(0));

        if (droppedFiles) {
          $.each( droppedFiles, function(i, file) {
            $(".box__file").prop("files")[0] = file;
          });
        }
        $form.trigger('submit');
      });
    }
    $(".box__file").on("input change drop",function(){
      $form.trigger('submit');
    });
    $form.on("submit",function(e){
      e.preventDefault();
      $form.addClass("invisible");
      $(".box__input").hide();
      image(document.getElementById("file"),www,hhh,".imageid");
    });
    $("#active_language").on("change",function(){
        active = $(this).val();
        var iframe = document.getElementById("iframe")
        var doc = iframe.contentDocument || iframe.contentWindow.document;
        $iframe.attr('src','http://'+ getDomain() + "/lang/" + active);
    });
    $("#text_edit_save").click(function(){
      var text = document.querySelector(".ql-editor").innerHTML;
      var id = $(".ql-editor").attr("edit-id");
      var iframe = document.getElementById("iframe")
      var doc = iframe.contentDocument || iframe.contentWindow.document;
      var elem = doc.querySelectorAll('[edit="'+id+'"]');
      for(i = 0; i < elem.length; i++){
        elem[0].innerHTML = text;
      }
      var language = $("#active_language").val();
      save(id,text,language);
      closeTextEditor();
    });
    $("#image_edit_save").on("click",function(){
      var file_data = $("#file").prop("files")[0];
      $.ajax({
        url: "/Felta/edit/image",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        async: false,
        data: {
          "id": $("#id").val(),
          "file_name": file_data,
          "language": $("#active_language").val(),
          "w": imageCords.w,
          "h": imageCords.h,
          "x1": imageCords.x1,
          "y1": imageCords.y1,
          "x2": imageCords.x2,
          "y2": imageCords.y2
        },
        success: function(data){
          var iframe = document.getElementById("iframe")
          var doc = iframe.contentDocument || iframe.contentWindow.document;
          var elem = doc.querySelectorAll('[edit="'+$("#id").val()+'"]');
          for(i = 0; i < elem.length; i++){
            console.log("source");
            elem[0].src = data.trim();
          }
          closeImageEditor();
        }
      });
    });
    $("#line_edit_save").on("click",function(){
      var text = $("#line_editor_value").val();
      var id = $("#line_editor_value").attr("edit-id");
      var iframe = document.getElementById("iframe")
      var doc = iframe.contentDocument || iframe.contentWindow.document;
      var elem = doc.querySelectorAll('[edit="'+id+'"]');
      for(i = 0; i < elem.length; i++){
        elem[0].innerHTML = text;
      }
      save(id,text,$("#active_language").val());
      closeLineEditor();
    });
});

function editor(id){
    var iframe = document.getElementById("iframe");
    var doc = iframe.contentDocument || iframe.contentWindow.document;
    var lastloc = null;
    var id = null;
    $(doc).on('mousemove',function(e){
        if(lastloc != null){
          lastloc.removeAttr("style");
          lastloc = null;
        }
        var atr = $(e.target);
        var hover = false;
        if (typeof atr.attr("edit") !== typeof undefined && atr.attr("edit") !== false) {
            hover = true;
            atr = $(e.target);
        }else if (atr.parents("[edit]").length){
            hover = true;
            atr = $(e.target).parents("[edit]");
        }
        if(hover){
          atr.css({
            "outline": "2px dashed #2196F3",
            "cursor": "pointer"
          });
         lastloc = atr;
        }     
    });
    $(doc).click(function(e){
        atr = "";
        if (typeof $(e.target).attr("edit") !== typeof undefined && $(e.target).attr("edit") !== false) {
            atr = $(e.target);
        }else if ($(e.target).parents("[edit]").length){
            atr = $(e.target).parents("[edit]");
        }
        www = $(e.target).width();
        hhh = $(e.target).height();
        if(atr[0]["tagName"] == null){
            return;
        }
        switch(atr[0]["tagName"]){
          case "IMG":
            openImageEditor();
            id = atr[0].getAttribute("edit");
            $("#id").val(id);
            $('html,body').animate({
                scrollTop: 0
            }, 1);
            break;
          case "IFRAME":
            openLinkEditor();
            id = atr[0].getAttribute("edit");
          break;
          case "A":
            openLinkAndNameEditor();
            id = atr[0].getAttribute("edit");
          break;
          case "H1":
          case "H2":
          case "H3":
            openLineEditor();
            id = atr.attr("edit");
            $.ajax({ 
                url: '/felta/edit/id/'+id+'/lang/'+$("#active_language").val(),
                type: "GET",
                beforeSend: function(){
                    isLoading = true;
                },
                success: function(output) {
                    $("#line_editor_value").attr("edit-id",id);
                    $("#line_editor_value").val(output.trim());
                }
            });
            $('html,body').animate({
                scrollTop: 0
            }, 1);
          break;
          default:
            id = atr.attr("edit");
            if(atr != ""){
                openTextEditor();
                $.ajax({ 
                url: '/felta/edit/id/'+id+'/lang/'+$("#active_language").val(),
                type: "GET",
                beforeSend: function(){
                    isLoading = true;
                },
                success: function(output) {
                    $(".ql-editor").attr("edit-id",id);
                    quill.pasteHTML(output.trim());
                }
                });
                $('html,body').animate({
                    scrollTop: 0
                }, 1);
            }
          break;
            
        }
        return;
    });
}
function image(input,width,height, id){
    var result = "";
    $image = $(".imageid");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $image.attr('src', e.target.result);
            result = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
    $image.on("load",function(){
      $image.hide();
      var y = ($image.width() / 2) - width;
      var h = ($image.height());
      var scale = width/height;
      $image.Jcrop({
        aspectRatio: width/height,
        onSelect: showCoords,
        onChange: showCoords,
        setSelect: [h*scale,h,0,0],
        boxWidth: $("#imageeditor").width(),
        boxHeight: 600
      },function(){
          jcrop = this;
      });
    });
}
function showCoords(c){
    imageCords.x1 = c.x;
    imageCords.y1 = c.y;
    imageCords.x2 = $image.width();
    imageCords.y2 = $image.height();
    imageCords.w = c.w;
    imageCords.h = c.h;
}

function destroy(img){
    $(".imageid").attr("src",null);
    $(".imageid").removeAttr("style");
    $(".imageid").fadeOut();
    document.getElementsByClassName("select-image")[0].reset();
    JcropAPI = $image.data('Jcrop');
    JcropAPI.destroy();
}

function getDomain(){
    if(document.domain.length){
        var parts = document.domain.replace(/^(www\.)/,"").split('.');
        while(parts.length > 2){
            var subdomain = parts.shift();
        }
        var domain = parts.join('.');
        return domain.replace(/(^\.*)|(\.*$)/g, "");
    }
    return '';
}

function save(id,text,language){
    $.ajax({
        url: "/Felta/edit",
        type: "POST",
        data: {id: id,text: text,language:language},
    });
}


/*

 Open editors

*/

function openTextEditor(){
  $("#text_edit_buttons").show();
  $("#text_editor").fadeIn();
  closeBasicButtons();
}
function closeTextEditor(){
  $("#text_edit_buttons").hide();
  $("#text_editor").fadeOut();
  quill['editor']['scroll']['domNode'].innerHTML = '';
  openBasicButtons();
}
function openImageEditor(){
    $('.select-image').removeClass("invisible");
    $(".box__input").show();
    $("#image_edit_buttons").show();
    $("#image_editor").fadeIn();
    closeBasicButtons();
}
function closeImageEditor(){
    $("#image_edit_buttons").hide();
    $("#image_editor").fadeOut();
    destroy();
    openBasicButtons();
}
function openLineEditor(){
    $("#line_edit_buttons").show();
    $("#line_editor").fadeIn();
    closeBasicButtons();
}
function closeLineEditor(){
    $("#line_edit_buttons").hide();
    $("#line_editor").fadeOut();
    openBasicButtons();
}
function openLinkEditor(){

    closeBasicButtons();
}
function closeLinkEditor(){

    openBasicButtons();
}
function openBasicButtons(){
    $("#basic_buttons").show();
}
function closeBasicButtons(){
    $("#basic_buttons").hide();
}