<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta charset="utf-8">
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
          <a id="HOME" href="{{ url('/welcome') }}" class="w3-bar-item w3-button w3-hover-teal">HOME</a>
          <a id="ADD" href="{{ url('/Add_course') }}" class="w3-bar-item w3-button w3-hover-teal">ADD COURSE</a>
          <a id="REC" href="{{ url('/recommender') }}" class="w3-bar-item w3-button w3-hover-teal">RECOMMEND</a>
        </div>
      <div zclass="w3-main" id="main">
        <div class="w3-card-4">
        <div class="w3-bar w3-green">
          <button  id="hamburger" class="w3-bar-item w3-button w3-xlarge w3-mobile w3-light-green w3-hover-teal" onclick="w3_open()">&#9776;</button><a id="Project_name" class="w3-bar-item w3-mobile w3-xlarge" >Course Recommender Application</a>
              @if(Sentinel::check())
                <a id="log_out" href="{{ url('/sign-out') }}" class="w3-bar-item w3-button w3-red w3-mobile w3-xlarge w3-right w3-hover-red" > <span class="glyphicon glyphicon-log-out"></span> Log out</a>
                <a id="user_name" class="w3-bar-item w3-button w3-mobile w3-hover-teal w3-xlarge w3-right" ><b>Welcome back,</b>{{ Sentinel::getUser()->first_name}} !</a>
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
      <div class="w3-container">
        <div class="w3-card-4 w3-light-green">
          <p id="title_name" class="w3-xlarge">&nbsp;Summary of Course</p>
            <div id="old_course_table">
              <table  id="no-more-tables" class="w3-table-all">
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
    </body>
</html>
