function w3_open() {
  var x = document.getElementById("mySidebar")
  if (x.style.display === 'none') {
       x.style.display = 'block';
   } else {
       x.style.display = 'none';
   }

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
            <td style="width:10%;"><button id="btn_remove" data-id3="`+data[i].course_id+`,`+data[i].course_name+`"  class="w3-btn w3-red"> REMOVE  </button> </td>
            </tr>
          `;
  }
  string +=`</tbody></table><br>`;
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
         <table class="w3-table-all" id="no-more-tables">
           <thead>
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
  console.log(course_array[0]);
  if(confirm("Are you sure you want to remove this course?"))
  {
    $.ajax({
         url:"welcome",
         type:"POST",
         data:{_token: CSRF_TOKEN, course_id: course_array[0],course_name: course_array[1]},
         dataType:"json",
         success:function(data)
         {
           console.log(data);
           fetch_data();
         },
         error: function (data) {
             console.log(data);
         }
    })
 }else{
   $(this).show();
 }
});
$(window).on('resize', function() {
  var win = $(this);
  if (win.width() < 400) {
    $('#info').remove('w3-display-top w3-padding');
    $('#info').addClass('w3-display-right w3-padding');
  }
});
