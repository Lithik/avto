$(document).ready(function() {
  $('#files').hide();
  var button = $('#uploadButton'), interval;

  $.ajax_upload(button, {
    action : '/upload.php',
    name : 'myfile',
    onSubmit : function(file, ext) {
              // показываем картинку загрузки файла
              $("img#load").attr("src", "img/load.gif");
              $("#uploadButton font").text('Загрузка');

              /*
               * Выключаем кнопку на время загрузки файла
               */
               this.disable(); 

             },
             onComplete : function(file, response) {
              // убираем картинку загрузки файла
              $("img#load").attr("src", "img/loadstop.gif");
              $("#uploadButton").hide();

              // снова включаем кнопку
              // this.enable();

              // показываем что файл загружен
              $('#files').show();
              $("<li>" + file + "</li>").appendTo("#files");

              $('#img_form').attr('value', file);
              // $('#img_form').slideUp('slow');

              // var a = "<? echo $result ?>";
              // $a.appendTo("#files");


            }
          });
});
