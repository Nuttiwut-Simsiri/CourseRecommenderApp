function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}
function create_table(data){
  var string ="";
  var rating2grade = {'4':'A','3.5':'B+','3' : 'B','2.5':'C+','2' :'C','1.5':'D+','1' :'D','0' :'F'};
  for(i = 0; i < data.length; i++)
  {

        string +=
          `
            <tr>
            <td style="width:3%" id="id">`+(i+1)+`</td>
            <td style="width:10%" id="course_id">`+data[i].course_id+`</td>
            <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
            <td style="width:3%" id="course_grade">`+rating2grade[data[i].rating]+`</td>
            <td style="width:10%;"><button id="btn_remove" data-id3="`+data[i].course_id+`,`+data[i].course_name+`"  class="btn btn-danger"> REMOVE  </button> </td>
            </tr>
          `;
  }
  string +=`</tbody></table>`;
  return string;
}
function fetch_data()
{
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  $("#result_table").empty();
  $("#old_course_table").empty();
  $.ajax({
       url:"/query",
       type:"POST",
       data:{_token: CSRF_TOKEN},
       success:function(data){
         var start="";
         start +=
         `
         <h3> Summary of Course  </h3>
         <table class="table">
           <thead class="thead-inverse">
             <tr>
               <th>  #          </th>
               <th>  COURSE ID  </th>
               <th>  COURSE NAME </th>
               <th>  GRADE   </th>
               <th>  REMOVE   </th>
             </tr>
           </thead>
             <tbody>
         `;
         var table_string = start + create_table(data);
         $("#old_course_table").append(table_string);
       },
       error: function (data) {
           console.log('Error:', data);
       }
  });
}
$(document).on('click', '#btn_remove', function(){
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  $(this).hide();
  var data = $(this).data("id3");
  var course_array = data.split(',');
  console.log(course_array);
  if(confirm("Are you sure you want to remove this course?"))
  {
    $.ajax({
         url:"/remove",
         type:"POST",
         data:{_token: CSRF_TOKEN, course_id: course_array[0], course_name: course_array[1] },
         dataType:"json",
         success:function(data)
         {
           console.log(data);
           fetch_data();
         },
         error: function (data) {
             console.log(data);
             $("#status").html(data);
         }
    })
 }else{
   $(this).show();
}
});
$(document).ready(function () { // wait until the document is ready
  //$("#result_table").append('<image id="pic" img src="/img/recommend-img.jpg" height="240" width="216" ></image><br><br>');
  $('#recommend_button').click(function(){
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
        $("#result_table").append('<p id="process"> Processing your Recommendation </p>');
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
    $("#result_table" ).empty();
    $("#old_course_table" ).empty();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: 'welcome',
        data: {_token: CSRF_TOKEN},
        success: function(data) {
            var myObj = JSON.parse(data);
            console.log(myObj);
            var start="";
            start +=
            `
            <table class="table table-bordered"  width="70%">
              <thead align="center">
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
