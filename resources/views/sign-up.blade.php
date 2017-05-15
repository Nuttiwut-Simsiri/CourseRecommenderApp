<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/w3.css" rel="stylesheet">
    <link href="css/sign-up.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </head>
  @if (count($errors) > 0)
     <div class="w3-panel w3-red w3-display-container">
     <span onclick="this.parentElement.style.display='none'"class="w3-button w3-red w3-large w3-display-topright">&times;</span>
        <ul>
           @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
           @endforeach
        </ul>
     </div>
  @endif
  <div class="container">
    <br>
    <div class="w3-display-container">
	     <div class="w3-card-4">
           <div class="w3-container w3-green">
             <h2>SIGN UP FORM</h2><br>
           </div><br><br>
           <form action="/sign-up" method="POST" class="w3-container">
             {{ csrf_field() }}
             <label>First Name * :</label>
             <input class="w3-input" type="text" name="first_name" placeholder="First Name" value="{{ Request::old('first_name')}}" required >

         		<label>LastName * : </label>
           	<input class="w3-input" type="text"  name="last_name" placeholder="Last Name" value="{{ Request::old('last_name')}}" required >

         		<label>Student ID * : </label>
           	<input class="w3-input" type="text" name="student_ID" value="{{ Request::old('student_ID')}}" placeholder="Student ID">

         		<label>Password * : </label>
           	<input class="w3-input" type="password"  name="password" placeholder="Password" required>

         		<label for="Password">ConfirmPassword * : </label>
           	<input class="w3-input" type="password"  name="password_confirmation" placeholder="Insert password again" required>

         		<label>E-mail  : </label>
           	<input class="w3-input" type="text"  name="email"  value="{{ Request::old('email')}}" placeholder="example@xyz.com">
             <br><br>
               <div class="w3-center">
                 <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
       		      <input type="submit" id="sign-up" class="w3-btn w3-green w3-hover-teal" value="Sign up" style="width:30%">
       		      <input type="button" id="cancel" class="w3-btn w3-red  w3-hover-pink" value="Cancel" onclick="location.href='/';" style="width:30%">
                 <br><br>
               </div>
          </div>
        </div>
      </div>


		  </form>
</html>
