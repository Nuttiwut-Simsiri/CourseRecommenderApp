<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0 user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Course Recommender Application</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/w3.css" rel="stylesheet">
    <link href="css/index-page.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <head>
      @if (Session::has('message'))
      <div class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong> Sign up Sucessfully !</strong> ..{{ Session::get('message') }}..
        </div>
      @endif
      @if (count($errors) > 0)
           <div class = "alert alert-danger alert-dismissable">
             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <ul>
                 @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                 @endforeach
              </ul>
           </div>
        @endif

    </header>
    <body>
    <div class="w3-display-container">
      <div class="w3-padding w3-display-topright">
        <a class="w3-btn w3-ripple w3-blue" id="sign-in" onclick="document.getElementById('login-modal').style.display='block'">Sign in</a>
        <a href="../sign-up" class="w3-btn w3-ripple w3-teal"> Sign up</a>
      </div>
      <img src="../img/background-img.jpg" style="width:100%;height:100vh;">
      <div class="w3-padding w3-display-left w3-margin-left">
        <h1> Welcome to <font color="#500F26">CRA </font> !</h1>
        <h4>CRA is Course Recomender Application,It's new course adviser </h4>
        <button type="button" class="btn btn-info" onclick="location.href='/info';">Learn More</button>
      </div>
      <div class="w3-padding w3-display-bottommiddle">
          <p>Computer Engineering ,KMUTNB </p>
      </div>
    </div>
        <div class="w3-modal" id="login-modal">
          <div class="w3-modal-content w3-card-4">
            <header class="w3-container w3-light-blue">
              <br>
              <span onclick="document.getElementById('login-modal').style.display='none'"class="w3-button w3-display-topright w3-hover-red">&times;</span>
              <h2>Sign in</h2>
              <br>
            </header>
            <div class="w3-container">
              <br>
              <form action="/sign-in" method="post" class="w3-container">
                {{ csrf_field()}}
                <label>STUDENT ID :</label>
                <input  class="w3-input" type="text" name="student_ID" placeholder="Student ID" required />
                <br>
                <label>PASSWORD :</label>
                <input  class="w3-input" type="password" name="password" placeholder="Password" required />
                <br>
            </div>
            <div class="w3-center">
              <input  type="submit" class="w3-btn w3-blue" value="Sign in" style="width:50%">
            </div>
            <br>
            <footer class="w3-container w3-cyan">
                <p class="w3-center"> Course Recommender Application</p>
            </footer>
            </form>
          </div>
        </div>
      </div>
  </body>
</html>
