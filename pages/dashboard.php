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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
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
       
       <div class="item">
         <a class="sub-btn"><i class="fas fa-user"></i>Friends<i class="fas fa-angle-right dropdown"></i></a>
         <div class="sub-menu">
           <a href="#" class="sub-item">Friend 01</a>
           <a href="#" class="sub-item">Friend 02</a>
           <a href="#" class="sub-item">Friend 03</a>
         </div>
       </div>
       
       <div class="item">
         <a class="sub-btn"><i class="fas fa-users"></i>Groups<i class="fas fa-angle-right dropdown"></i></a>
         <div class="sub-menu">
          <a href="#" class="sub-item">Group 01</i></a>
          <a href="#" class="sub-item">Group 02</i></a>
         </div>
       </div>
     </div>
   </div>
   <section class="main">
     <h1>Sidebar Menu With<br>Sub-Menus</h1>
   </section>

   <script type="text/javascript">
   $(document).ready(function(){
     //jquery for toggle sub menus
     $('.sub-btn').click(function(){
       $(this).next('.sub-menu').slideToggle();
       $(this).find('.dropdown').toggleClass('rotate');
     });

     //jquery for expand and collapse the sidebar
     $('.menu-btn').click(function(){
       $('.side-bar').addClass('active');
       $('.menu-btn').css("visibility", "hidden");
     });

     $('.close-btn').click(function(){
       $('.side-bar').removeClass('active');
       $('.menu-btn').css("visibility", "visible");
     });
   });
   </script>

 </body>
</html>
