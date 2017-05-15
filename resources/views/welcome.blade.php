<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="HandheldFriendly" content="true">
        <title>Course Recomender Appication :: Welcome !</title>

        <!-- scripts -->

        <script src="js/jquery-3.2.0.min.js"></script>
        <script src="js/home-page.js"></script>
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/home-page.css">
        <!-- Styles -->
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
          <a href="{{ url('/welcome') }}" class="w3-bar-item w3-button w3-hover-teal">HOME</a>
          <a href="{{ url('/Add_course') }}" class="w3-bar-item w3-button w3-hover-teal">ADD COURSE</a>
          <a href="{{ url('/edit_profile') }}" class="w3-bar-item w3-button w3-hover-teal">EDIT</a>
        </div>
      <div zclass="w3-main" id="main">
        <div class="w3-card-4">
        <div class="w3-bar w3-green">
          <button class="w3-bar-item w3-button w3-xlarge w3-mobile w3-hover-light-green" onclick="w3_open()">&#9776;Course Recommender Application</button>
              @if(Sentinel::check())
              <a href="{{ url('/sign-out') }}" class="w3-bar-item w3-button w3-hover-red w3-mobile w3-xlarge w3-right"> <span class="glyphicon glyphicon-log-out"></span> Log out</a>
                <a  class="w3-bar-item w3-button w3-mobile w3-hover-teal w3-xlarge w3-right"><b>Welcome back,</b>{{ Sentinel::getUser()->first_name}} !</a>
              @else
                <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://localhost:8000/">
              @endif
          </div>
        </div>
      </div>
      <div class="w3-container">
        <div class="w3-display-container w3-green w3-card-4 w3-margin-top"  style="height:150px;">
          <div class="w3-display-left w3-padding">
            <img id="user" src="../img/user.png" >
          </div>
          <div id="info" class="w3-display-middle w3-padding">
            <b>Name : {{ Sentinel::getUser()->first_name}} <br></b>
            <b>Surname : {{ Sentinel::getUser()->last_name}}</b>
          </div>
          <div id="date" class="w3-display-top w3-padding">
              <p id="date"> </p>
          </div>
        </div>
      </div>
      <div id="wait" style="display:none;" class="loader"></div>
      <div class="w3-container">
        <div class="w3-card-4 w3-light-green">
          <p class="w3-xlarge">&nbsp;Summary of Course</p>
            <div id="old_course_table">
              <table  class="w3-table-all" id="no-more-tables">
                <thead>
                  <tr>
                    <th>  #</th>
                    <th>  COURSE ID   </th>
                    <th>  COURSE NAME </th>
                    <th>  GRADE </th>
                    <th>  REMOVE </th>
                  </tr>
                </thead>
                <tbody>
                  {!!$table!!}
          </div>
        </div>
      </div>
      <div class="w3-container">
        <div id="result_table"></div><br><br>
      </div>
      <div class="w3-container">
        <div class="w3-center">
          <button class="w3-btn w3-green w3-large" id="recommend_button" > RECOMMEND </button>
        </div>
      </div>
    </body>
</html>
