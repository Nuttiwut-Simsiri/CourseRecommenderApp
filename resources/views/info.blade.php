<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Course Recommender Application</title>
        <script src="js/jquery.min.js"></script>
        <script src="js/home-page.js"></script>
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/home-page.css">
    </head>
    <body>
    <div class="w3-display-container">
      <div class="w3-card-4">
        <div class="w3-container w3-blue w3-xlarge">
          <h1 class="w3-xxlarge"> Infomation about CRA</h1>
        </div>
      </div>
      <br>
      <div class="w3-card-4">
        <div class="w3-container w3-center">
          <br>
          <img src="../img/recommend-img.jpg"  class="w3-circle" style="width:15%">
          <h2 class="w3-jumbo"><font color="red">C</font>ourse </h2>
          <h2 class="w3-jumbo"><font color="red">R</font>ecommender </h2>
          <h2 class="w3-jumbo"><font color="red">A</font>pplication </h2>
        </div>
      </div>
      <div >
        <br>
        <div class="w3-container w3-center">
          <button id="back" class="w3-btn w3-green " onclick="location.href='/';" style="width:50%">  home </button>
        </div>
    </div>
    </body>
</html>
