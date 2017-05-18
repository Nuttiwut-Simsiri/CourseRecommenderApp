function create_table(data){
  var string ="";
  var tableU ="";
  console.log(data['ITEM'].length);
  console.log(data['USER'].length);
  tableU +=
  `

  <p id="user-based" class="w3-large w3-text-white">&nbsp;User based Recommendation</p>
  <table id="unseen" class="w3-table-all">
    <thead>
      <tr>
        <th>Ranking</th>
        <th>Course name</th>
        <th>Grade</th>
      </tr>
    </thead>
      <tbody>
  `
  ;
    var j = 0;
    for(i = 0; i < data['ITEM'].length; i++)
    {
      string +=
            `
              <tr>
              <td style="width:3%" id="id">`+(i+1)+`</td>
              <td style="width:10%" id="course_name">`+data['ITEM'][i].course_name+`</td>
              <td style="width:3%" id="course_grade">`+data['ITEM'][i].rating+`</td>
              </tr>
            `;
    }
    string +=`</tbody></table>`;
    for(i = 0; i < data['USER'].length; i++)
    {
      tableU +=
            `
              <tr>
              <td style="width:3%" id="id">`+(i+1)+`</td>
              <td style="width:10%" id="course_name">`+data['USER'][i].course_name+`</td>
              <td style="width:3%" id="course_grade">`+data['USER'][i].rating+`</td>
              </tr>
            `;
    }

    tableU +=`</tbody></table><br>`;
    var result = "";
    result = string+tableU;
    return result;

}
$(window).ready(function(){
  if ($(window).width()  <= 400) {
    $('#info').removeClass('w3-display-top w3-padding');
    $('#info').addClass('w3-display-right w3-padding');

    $('#hamburger').removeClass('w3-bar-item w3-button w3-xlarge w3-mobile w3-light-green w3-hover-teal');
    $('#Project_name').removeClass('w3-bar-item w3-mobile w3-xlarge');
    $('#user_name').removeClass('w3-bar-item w3-button w3-mobile w3-hover-teal w3-xlarge w3-right');
    $('#log_out').removeClass('w3-bar-item w3-button w3-red w3-mobile w3-xlarge w3-hover-red  w3-right');

    $('#hamburger').addClass('w3-bar-item w3-button w3-xlarge w3-mobile w3-light-green w3-hover-teal w3-medium');
    $('#Project_name').addClass('w3-bar-item w3-mobile w3-medium');
    $('#user_name').addClass('w3-bar-item w3-button w3-mobile w3-hover-teal w3-right w3-medium');
    $('#log_out').addClass('w3-bar-item w3-button w3-red w3-mobile w3-hover-red w3-right w3-medium');

    $('#recommend_button').removeClass('w3-large');
    $('#recommend_button').addClass('w3-medium');

    $('#unseen').addClass('w3-small');

    $('#item_based').removeClass('w3-large');
    $('#user_based').removeClass('w3-large');

    $('#item_based').addClass('w3-medium');
    $('#user_based').addClass('w3-medium');

    $('#process_text').removeClass('w3-large');
    $('#process_text').addClass('w3-small');

  }
});
$(document).ready(function () {
  $('#recommend_button').click(function(){
    $("#unseen" ).empty();
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
        $("#process").css("display", "block");
        $('#loading').css("display","block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
        $("#process").css("display", "none");
        $('#loading').css("display","none");
    });
    $("#img" ).empty();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: 'recommend',
        data: {_token: CSRF_TOKEN},
        success: function(data) {
            var json = JSON.parse(data);
            console.log(json);
            var start="";
            start +=
            `
            <br>
            <p id="item-based" class="w3-large w3-text-white">&nbsp;Item based Recommendation</p>
            <table class="w3-table-all" id="unseen" >
              <thead>
                <tr>
                  <th>Ranking</th>
                  <th>Course name</th>
                  <th>Grade</th>
                </tr>
              </thead>
                <tbody>
            `;

            var table_string = start + create_table(json);
            $("#unseen" ).empty();
            $("#unseen").append(table_string);

        },
        error: function (jqXHR, exception) {
             var msg = '';
             if (jqXHR.status === 0) {
                 msg = 'Not connect.\n Verify Network.';
             } else if (jqXHR.status == 404) {
                 msg = 'Requested page not found. [404]';
             } else if (jqXHR.status == 500) {
                 msg = 'Internal Server Error [500].';
             } else if (exception === 'parsererror') {
                 msg = 'Requested JSON parse failed.';
             } else if (exception === 'timeout') {
                 msg = 'Time out error.';
             } else if (exception === 'abort') {
                 msg = 'Ajax request aborted.';
             } else {
                 msg = 'Uncaught Error.\n' + jqXHR.responseText;
             }
             $("#result_table").empty();
             $("#result_table").html(msg);
         },
    }); // End Ajax
  }); // End onclick

});
