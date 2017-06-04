<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">

        <title>Course Recomender Appication :: Welcome !</title>

        <!-- scripts -->
        <script src="js/jquery-3.2.0.min.js"></script>
        <link rel="stylesheet" href="css/w3.css">

        <!-- Styles -->
        <style>

        @media only screen and (max-width: 800px) {
        	#unseen table td:nth-child(1),
        	#unseen table th:nth-child(1) {display: none;}
        }

        @media only screen and (max-width: 640px) {
        	#unseen table td:nth-child(1),
        	#unseen table th:nth-child(1){
            display: none;
          }
        }
        </style>
        <script>
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
          for(i = 0; i < data.length; i++)
          {
                string +=
                  `
                    <tr id="`+data[i].course_name+`">
                      <td style="width:10%" id="course_id">`+data[i].course_id+`</td>
                      <td style="width:10%" id="course_name">`+data[i].course_name+`</td>
                      <td style="width:10%" id="course_grade">`+
                      `
                      <select class="w3-select w3-border" required>
                          <option selected disabled> Select grade</option>
                          <option value="4"> A  </option>
                          <option value="3.5"> B+ </option>
                          <option value="3"> B  </option>
                          <option value="2.5"> C+ </option>
                          <option value="2"> C  </option>
                          <option value="1.5"> D+ </option>
                          <option value="1"> D  </option>
                      </select>
                      `
                      +`</td>
                      <td style="width:10%"><button id="btn_enroll" data-id3="`+data[i].course_id+`,`+data[i].course_name+`" class="w3-btn w3-green"> ADD  </button> </td>
                    </tr>`;
          }
          string +=
                    `
                    </tbody>
                  </table>
                    `;
          return string;
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

            $('#unseen').addClass('w3-small');


          }
        });
          $(document).ready(function(){

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('#search-box').keyup(function () {
            var key = $(this).val();
            if(key.length >=2) {
              $.ajax({
                  type: 'POST',
                  url: 'search',
                  data: {Key_search :key,_token:CSRF_TOKEN},
                  dataType: 'json',
                  success: function(data) {
                    console.log(data);
                    var start="";
                    start +=
                    `
                    <table class="w3-table-all" id="unseen">
                      <thead>
                        <tr>
                          <th>  COURSE ID   </th>
                          <th>  COURSE NAME</th>
                          <th>  GRADE       </th>
                          <th>  ADD      </th>
                        </tr>
                      </thead>
                        <tbody>

                    `;
                    $("#unseen ").empty();
                    $("#status").empty();
                    var table_string = start + create_table(data);
                    $("#unseen ").append(table_string);

                  },
                  error: function (data) {
                      console.log('Error:', data);
                  }
              });
          }else if(key.length ==0){
            $("#unseen ").empty();
          }
        });
        $(document).on('click', '#btn_enroll', function(){
           var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
           var data = $(this).data("id3");
           var course_array = data.split(',');
           var grade = $(this).closest('tr').find('#course_grade').find('select').val();
           course_array.push(grade);
           $(this).hide();
           if(grade != null){
             $.ajax({
                  url:"/insert",
                  type:"post",
                  data:{_token: CSRF_TOKEN, course_id: course_array[0], course_name: course_array[1],course_grade: course_array[2]},
                  dataType:"json",
                  success:function(data)
                  {

                    console.log(data);
                    $("#status").html(data);
                  },
                  error: function (data) {
                      $(this).show();
                      console.log('Error:', data);
                      $("#status").html(data);
                  }
                })
           }else{
             alert("Please select grade before add!");
             $(this).show();
           }
          });


      });

        </script>

    </head>

    <body>
      @if (Session::has('message'))
      <div class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          ..{{ Session::get('message') }}..
      </div>
      @endif
      <div class="w3-sidebar w3-bar-block w3-light-grey w3-animate-left" style="display:none" id="mySidebar">
        <button class="w3-bar-item w3-button w3-large w3-hover-red" onclick="w3_close()">Close &times;</button>
        <a id="HOME" href="{{ url('/welcome') }}" class="w3-bar-item w3-button w3-hover-teal">HOME</a>
        <a id="ADD" href="{{ url('/Add_course') }}" class="w3-bar-item w3-button w3-hover-teal">ADD COURSE</a>
        <a id="REC" href="{{ url('/recommender') }}" class="w3-bar-item w3-button w3-hover-teal">RECOMMEND</a>
      </div>

      <div class="w3-card-4">
        <div class="w3-bar w3-green">
          <button  id="hamburger" class="w3-bar-item w3-button w3-xlarge w3-mobile w3-light-green w3-hover-teal" onclick="w3_open()">&#9776;</button><a id="Project_name" class="w3-bar-item w3-mobile w3-xlarge" >Course Recommender Application</a>
              @if(Sentinel::check())
              <a id="log_out" href="{{ url('/sign-out') }}" class="w3-bar-item w3-button w3-red w3-mobile w3-xlarge w3-right w3-hover-red" > <span class="glyphicon glyphicon-log-out"></span> Log out</a>
                <a  id="user_name" class="w3-bar-item w3-button w3-mobile w3-hover-teal w3-xlarge w3-right" ><b>Welcome back,</b>{{ Sentinel::getUser()->first_name}} !</a>
              @else
                <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://localhost:8000/">
              @endif
        </div>
      </div>

      <div class="w3-container">
        <div class="w3-card-4">
            <h4>SEARCH COURSE & ADD </h4>
            <input class="w3-input"  id="search-box" placeholder="search by course name" >
        </div>
      </div>
      <div class="w3-container">
        <div class="w3-panel w3-pale-green w3-small">
          <p id="status">ready </P>
        </div>
      </div>
        <div class="w3-container">
          <div class="w3-card-4">
            <div id="unseen"> </div>
          </div>
      </div>
    </body>
</html>
