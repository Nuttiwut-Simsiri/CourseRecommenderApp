function create_table(data){
  var string ="";
  var tableU ="";
  console.log(data['ITEM'].length);
  console.log(data['USER'].length);
  tableU +=
  `

  <h4>&nbsp;User based Recommendation</h4>
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

$(document).ready(function () {
  $('#recommend_button').click(function(){
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
        $("#result_table").append('<p id="process"> Processing your Recommendation </p>');
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
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
            <h4>&nbsp;Item based Recommendation</h4>
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
