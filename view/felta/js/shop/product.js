var variants = 1;
var activeVariant = 1;

setupVariant("variant1");
setupTabs();

$("#image_edit_cancel, #image_editor_background").click(function(){
  closeImageEditor();
});

$("#cancelphoto").on("click",function(){
  closeImageEditor();
});

$("#add-tab").on("click", function(){
  addVariant();
});

$("#addphoto").on("click",function(){
    $('#imageid').croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (resp) {
      uploadImage(
        base64ToBlob(resp.replace(/^data:image\/(png|jpg);base64,/, ""),'image/png')
      ).then((response) => {
        json = JSON.parse(response);
        $("#image-selector").prepend(
          "<span image-id='"+json.uid+"'><span class='delete' onclick='deleteImage(this,\""+json.url+"\")' /><input type='hidden' name='variants["+(activeVariant -1)+"][images][]' value='"+json.url+"' /> <img src='"+json.url+"' /></span>"
        );
        closeImageEditor();
      });
    });
});


document.getElementById("amount").onblur =function (){    
    this.value = parseFloat(this.value.replace(/,/g, ""))
      .toFixed(2)
      .toString()
      .replace(/\B(?=(\d{3})+(?!\d))/g, "");
}

function deleteImage(e,url){
  $.ajax({
    url: "/felta/shop/delete/image",
    type: "POST",
    data: {
      url: url
    },
    success: function(response){
    }
  });
  $(e).parent().remove();
}

function addVariant(){
  variants++;
  activeVariant = variants;
  setupVariant("variant" + variants);
}

function removeVariant(variant){
  $(variant + "-tab").remove();
  $(variant).remove();
  variants--;
}

function setupVariant(id) {
  var variantTemplate = document.getElementById("variant");
  var newVariant = variantTemplate.cloneNode(true);
  
  if (variants > 1){
    var previousTab = document.getElementById("variant" + (variants - 1) + "-tab");
    var newTab = previousTab.cloneNode(true);
    newTab.setAttribute("id", "variant" + variants + "-tab");
    newTab.setAttribute("column", id);
    newTab.innerHTML = "Variant " + variants;
    previousTab.parentNode.insertBefore(newTab, previousTab.nextSibling);
  }

  newVariant.setAttribute("id", id);
  document.getElementById("variants-container").append(newVariant);

  $("#" + id + " #variant_name")[0].setAttribute("name", "variants[" + (variants - 1) + "][variant_name]");
  $("#" + id + " #currency")[0].setAttribute("name", "variants[" + (variants - 1)+ "][currency]");
  $("#" + id + " #amount")[0].setAttribute("name", "variants[" + (variants - 1) + "][amount]");
  $("#" + id + " #quantity")[0].setAttribute("name", "variants[" + (variants - 1) + "][quantity]");
  $("#" + id + " #quantity")[0].setAttribute("name", "variants[" + (variants - 1) + "][quantity]");
  $("#" + id + " #attributes")[0].setAttribute("id", "attributes" + (variants));
  $("#" + id + " #add-attribute")[0].setAttribute("id", "add-attribute" + (variants));

  $("#add-attribute" + (variants)).on("click", function(e) {
    e.preventDefault();
    var cVariants = variants -1 ;
    var parent = $('#attributes' + (variants));
    var template = $("#attributes" + (variants) + " #attribute-template")[0];
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
  setupTabs();
}

function setupTabs() {
  var active = "variant" + variants;
  var last = "variant1";
  $("#"+active).removeClass("hidden");
  $("#"+active+"-tab").addClass("active");
  $(".tab").on("click",function(e){
      last = active;
      $("#"+last).addClass("hidden");
      $("#"+last+"-tab").removeClass("active");
      active = $(this).attr("column");
      if (active) {
        activeVariant = parseInt(active.replace("variant"));
      }
      $("#"+active).removeClass("hidden");
      $("#"+active+"-tab").addClass("active");
  });
}