function create_table(data){
  var string ="";
  var tableU ="";
  tableU +=
  `
  <p class="w3-xlarge">&nbsp;User based Recommendation</p>
  <table class="w3-table-all">
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
  var rating2grade = {'4':'A','3.5':'B+','3' : 'B','2.5':'C+','2' :'C','1.5':'D+','1' :'D','0' :'F'};
  var j = 0;
  for(i = 0; i < 10; i++)
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
            var myObj = JSON.parse(data);
            console.log(myObj);
            var start="";
            start +=
            `
            <p class="w3-xlarge">&nbsp;Item based Recommendation</p>
            <table class="w3-table-all">
              <thead>
                <tr>
                  <th>Ranking</th>
                  <th>Course name</th>
                  <th>Grade</th>
                </tr>
              </thead>
                <tbody>
            `;

            var table_string = start + create_table(myObj);
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
