$(function(){
    $('#search').keyup(function(){
      var val = $(this).val().toLowerCase();
      $(".ranking tr").hide();
      $(".ranking tr").each(function(){
        var text = $(this).text().toLowerCase();
        if(text.indexOf(val) != -1)
        {
            $(".naglowek").show();
            $(this).show();
        }
      });
    });
  });