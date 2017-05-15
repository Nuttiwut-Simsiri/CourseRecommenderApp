function create_table(data){
  var string ="";
  var tableU ="";
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
  if(data.length>= 10 ){
    var j = 0;
    for(i = 0; i < data.length; i++)
    {

        if(i<5){
          string +=
            `
              <tr>
              <td style="width:3%" id="id">`+(i+1)+`</td>
              <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
              <td style="width:3%" id="course_grade">`+data[i].rating+`</td>
              </tr>
            `;
        }else if (i==5) {
          j++;
          string +=`</tbody></table>`;
          tableU +=
          `
            <tr>
            <td style="width:3%" id="id">`+(j)+`</td>
            <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
            <td style="width:3%" id="course_grade">`+data[i].rating+`</td>
            </tr>
          `;
        }else{
          j++;
          tableU +=
          `
            <tr>
            <td style="width:3%" id="id">`+(j)+`</td>
            <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
            <td style="width:3%" id="course_grade">`+data[i].rating+`</td>
            </tr>
          `;

        }

    }
    tableU +=`</tbody></table><br><br>`;
    var result = "";
    result = string+tableU;
    return result;
  }else {
    for(i = 0; i < data.lenght; i++)
    {
          string +=
            `
              <tr>
              <td style="width:3%" id="id">`+(i+1)+`</td>
              <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
              <td style="width:3%" id="course_grade">`+data[i].rating+`</td>
              </tr>
            `;

    }
    string +=`</tbody></table>`;
    return string;

}
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
            var JSON_data = JSON.parse(data)
            console.log(data);
            var start="";
            start +=
            `
              <br>
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

            var table_string = start + create_table(JSON_data);
            $("#result_table" ).empty();
            $("#result_table").append(table_string);

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
