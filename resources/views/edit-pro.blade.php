<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
      <script src="../js/jquery-3.2.0.min.js"></script>
      <script src="../js/recommend.js"></script>
      <link rel="stylesheet" href="../css/course.css">
      <link rel="stylesheet" href="css/w3.css">
    </head>
  <style>
  @media only screen and (max-width: 2000px) {
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  }
  @media only screen and (max-width: 800px) {
    #unseen table td:nth-child(3),
    #unseen table th:nth-child(3) {display: none;}
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  }

  @media only screen and (max-width: 640px) {
    #unseen table td:nth-child(3),
    #unseen table th:nth-child(3) {display: none;}
    .loader {
        border: 10px solid #f3f3f3; /* Light grey */
        border-top: 10px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 90px;
        height: 90px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        <a id="HOME" href="{{ url('/welcome') }}" class="w3-bar-item w3-button w3-hover-teal">HOME</a>
        <a id="ADD" href="{{ url('/Add_course') }}" class="w3-bar-item w3-button w3-hover-teal">ADD COURSE</a>
        <a id="REC" href="{{ url('/edit_profile') }}" class="w3-bar-item w3-button w3-hover-teal">RECOMMEND</a>
      </div>
    <div zclass="w3-main" id="main">
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
    </div>
    <div class="w3-container">
      <div class="w3-card-4 w3-green">
        <div class="w3-margin-left">
          <p id="recommend" class="w3-xlarge w3-text-white">RECOMMENDER APPLICATION :</p>
          <p id="description"  class="w3-medium">We recommended you with Cosine similarity and K-NN withMean prediction algorithm .User based ,Item based and Hybrid Recommender</p>
        </div>
      </div>
    </div>
    <div class="w3-container">
      <div id="loading" class="w3-display-container w3-white" style="height:250px;display:none;">
        <div class="w3-display-middle">
          <div id="wait" style="display:none;" class="loader"></div>
        </div>
        <div class="w3-display-bottommiddle">
          <br><br><br><br>
          <div id="process" style="display:none;" ><p id="process_text" class="w3-large" > Processing your Recommend... </p>
        </div>
      </div>
    </div>
    <div class="w3-container">
      <div class="w3-card-4 w3-light-green">
          <div id="unseen"></div>
      </div>
    </div>
    <div  class="w3-container">
      <div  class="w3-center">

        <div id="img">
          <img src="../img/recommend-img.jpg" id="img" style="width:20%;" >
        </div>
        <div class="w3-card-1">
          <br>
          <button id="recommend_button" class="w3-btn w3-green w3-large" id="recommend_button" > RECOMMEND </button>
        </div>
        <br>
      </div>
    </div>
</html>
