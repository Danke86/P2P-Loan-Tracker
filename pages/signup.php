<!DOCTYPE html>
<html>
  <head>
    <title>Signup - P2P</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="/group6_project127/css/styles.css"/>

  </head>
  <body>
    <div class="body-html">
      <nav>
        <div class="nav-wrapper teal darken-4">
          <a href="#" class="brand-logo"> Signup P2P</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="login.php">Login</a></li>
          </ul>
        </div>
      </nav>
      <div class="page-content-login"> 
        <div class="input-div">
          <form method="POST" action="../backend/signup_backend.php" onsubmit="return validation()">
            <div>
              <div class="input-field col s6">
                <input placeholder="Username" id="username" name="username" type="text" class="validate" required>
                <label for="username">Username</label>
              </div>
              <div class="input-field col s6">
                <input placeholder="Password" id="password" name="password" type="password" class="validate" required>
                <label for="password">Password</label>
              </div>
              <button class="btn waves-effect waves-light" type="submit" name="action">Submit</button>
            </div>
            <div class="signup-link">
              <a href="../index.php">Log in</a>
          </form>
        </div>
      </div>
    </div>
    
    
    <script>  
      function validation()  
      {  
          let username=document.getElementById("username").value
          let password=document.getElementById("password").value  
          if(username.length=="" && password.length=="") {  
              alert("Username and password fields are empty");  
              return false;  
          }  
          else  
          {  
              if(username.length=="") {  
                  alert("Username is empty");  
                  return false;  
              }   
              if (password.length=="") {  
                  alert("Password field is empty");  
                  return false;  
              }  
          }                             
      }  
    </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  </body>
</html>
