<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../js/jquery-3.2.0.min.js"></script>
        <link rel="stylesheet" href="../css/course.css">
        <link rel="stylesheet" href="css/w3.css">
    </head>
  <script>
  function w3_open() {
      document.getElementById("mySidebar").style.display = "block";
  }
  function w3_close() {
      document.getElementById("mySidebar").style.display = "none";
  }
  </script>
	<div class="container">
    @if (count($errors) > 0)
         <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
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
      <h2>Edit your Profile</h2><br>
    </div>
</html>
