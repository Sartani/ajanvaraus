<?php require_once("config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div  class="col-sm-2" id="side_left">  <?php include_once ("views/navbar.html") ?></div>
        <div class="col-sm-8" id="main"> <h1> WAAAT!</h1>
            
        <button type="button" onclick="NaytaKalenterit()" id="varaa" class="btn btn-primary btn-lg">Varaa aikoja</button>
        <button type="button" onclick="NaytaKirjautuminen()"id="katso" class="btn btn-secondary btn-lg">Katso varattuja</button>
        </div>
        <div class="col-sm-2" id="side_right"></div>
      </div>
    </div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" ></script>
    <script src="js/navigaatio.js"></script>
  </body>
</html>