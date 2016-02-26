    
    </div>
    <div class="container-fluid">
        <hr />
        <footer>
            <p class="text-center">&copy; 2014 <a href="http://at-develop.ru">Создание сайта — Apptech</a></p>
          </footer>
    </div>
    <script src="<?=base_url('themes/site/js/lightbox.js')?>"></script>

    <script>

    function send()
    {
        //Получаем параметры
        var id_gallery = $('#id_gallery').val();
        var id_image = $('#id_image').val();
        // Отсылаем паметры
        $.ajax({
        type: "POST",
        url: "/administrator/main_photo/",
        data: "id_gallery="+id_gallery+"&id_image="+id_image,
        // Выводим то что вернул PHP
        success: function(data) {
        //предварительно очищаем нужный элемент страницы
        $("#result").empty();
        //и выводим ответ php скрипта
        $("#result").append(data);
        }
        });
    }
</script>
    
    <script>
    $(document).ready(function() {
        $('.dataTable').dataTable( {
              "sScrollY": "600px",
              "bPaginate": false,
              "bScrollCollapse": true,
              "bInfo": true,
              "sDom": '<"toolbar">flipt',//frtip раньше так было
              "oLanguage": {
                "sSearch": "Фильтр: ",
                "sZeroRecords": "По запросу ничего не найдено",
                "sInfo": "Найдено записей: _END_ ",
                //"sInfo": "Показаны с _START_ по _END_ из _TOTAL_ страниц",
                "sInfoEmpty": "По запросу ничего не найдено",
                //"sInfoEmpty": "Показаны с 0 по 0 из 0 страниц",
                "sInfoFiltered": "<br /><br />"
                //"sInfoFiltered": "(filtered from _MAX_ total records)"
            }
        } );
      } );
    </script>
    <script>
$("a[rel=popover]")
    .popover({
    placement: "left",
    trigger: "click",
    html: true
  })
</script>


    </div>
  </body>
</html>