
<!-- HEADER OF EVERY PAGE (for the sidebar)
TO USE: include header.php ?> -->

<?php include('../backend/login_checker.php'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sidebar menu With Sub-menus | Using HTML, CSS & JQuery</title>
   <style media="screen">
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
   </style>
   <link rel="stylesheet" href="../css/styles.css">
   <link rel="stylesheet" href="../css/dashboard_style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
 
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
 <body>
   <div class="menu-btn">
     <i class="fas fa-bars"></i>
   </div>

   <div class="side-bar">
     <header>
     <div class="close-btn">
       <i class="fas fa-times"></i>
     </div>
     <img src="https://lh3.googleusercontent.com/a-/AOh14Gj99VObFyE8W_h8RrcwZO_aYiIHu5AAa_XpnOym=s600-k-no-rp-mo" alt="">
          <h1 id="user-email">admin@up.edu.ph</h1>
        </header>
     <div class="menu">
       <div class="item"><a href="#"><i class="fas fa-desktop"></i>Dashboard</a></div>
       <div class="item"><a href="#"><i class="fas fa-history"></i>Recent History</a></div>
       <div class="item"><a href="#"><i class="fas fa-user"></i>Friends</a></div>
       <div class="item"><a href="#"><i class="fas fa-users"></i>Groups</a></div>
     </div>
   </div>