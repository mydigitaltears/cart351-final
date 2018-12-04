<?php
    // if(!isset($_POST["code"])){
    //     die("Post was empty.");
    // }
    //
    // $sql="insert into designs(image) values(:image)";
    //
    // // INSERT with named parameters
    // $conn = new PDO('mysql:host=localhost;dbname=myDB', "root", "myPassword");
    // $stmt = $conn->prepare($sql);
    // $stmt->bindValue(":image",$_POST["image"]);
    // $stmt->execute();
    // $affected_rows = $stmt->rowCount();
    // echo $affected_rows;
    class MyDB extends SQLite3
    {
       function __construct()
       {
          $this->open('"/db/imageGallery.db');
       }
    }
    try
    {
       $db = new MyDB();
    }
    catch(Exception $e)
    {
        die($e);
    }


    //check if there has been something posted to the server to be processed
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
    // need to process
     $artist = $_POST['a_name'];
     $title = $_POST['a_title'];
     $loc = $_POST['a_geo_loc'];
     $creationDate = $_POST['a_date'];
     if($_FILES)
      {
        //echo "file name: ".$_FILES['filename']['name'] . "<br />";
        //echo "path to file uploaded: ".$_FILES['filename']['tmp_name']. "<br />";
       $fname = $_FILES['filename']['name'];
       move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);
        //echo "done";
        //package the data and echo back...

        // NEW:: add into our db ....
       //The data from the text box is potentially unsafe; 'tainted'.
    	 //We use the sqlite_escape_string.
    	 //It escapes a string for use as a query parameter.
    	//This is common practice to avoid malicious sql injection attacks.
    	$artist_es =$db->escapeString($artist);
    	$title_es = $db->escapeString($title);
    	$loc_es =$db->escapeString($loc);
    	$creationDate_es =$db->escapeString($creationDate);
    	// the file name with correct path
    	$imageWithPath= "images/".$fname;
      // for the new column
      $time = date("Y-m-d",time());
      $queryInsert ="INSERT INTO artCollection(artist, title, creationDate, geoLoc, image)VALUES ('$artist_es', '$title_es','$loc_es','$creationDate_es','$imageWithPath')";
      // again we do error checking when we try to execute our SQL statement on the db
    	$ok1 = $db->exec($queryInsert);
      // NOTE:: error messages WILL be sent back to JQUERY success function .....
    	if (!$ok1) {
        die("Cannot execute statement.");
        exit;
        }
        //send back success...
        echo "success";
        exit;
      }//FILES
    }//POST
?>

<!DOCTYPE HTML>
<html>
  <head>
  <script src="js/libraries/jquery-3.3.1.min.js"></script>
    <style>
      body {
        font-family: "Comic Sans MS", "Comic Sans", cursive;
      }
      #onloadText {
        text-align: center;
        top:50%;
        bottom:50%;
        position: relative;
        padding:15%;
      }
      #demo {
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE10+/Edge */
        user-select: none; /* Standard */
        color: blue;
      }
    </style>
  </head>
    <body>
      <div id="onloadText">
        <h1>Draw a monkey, you have 30 seconds</h1>
        <h1>Click to start!</h1>
      </div>
      <p id="demo"></p>
      <canvas id="canvas" width="800" height="600" style="position:absolute;top:0%;left:0%;"></canvas>
    <script>
      // $.ajax({
      //   type: "POST",
      //   url: "http://localhost/saveCanvasDataUrl.php",
      //   data: {image: dataUrl}
      // })
      // .done(function(respond){console.log("done: "+respond);})
      // .fail(function(respond){console.log("fail");})
      // .always(function(respond){console.log("always");})
      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');


      var start = (function(e){
        let gamestart = false;
        console.log("click");
        console.log(gamestart);
        return function() {
          if (!gamestart) {
              gamestart = true;
              // do something
              drawing();
          }
        };
      })();

      function drawing () {
          //console.log("gamestart");
          document.getElementById('onloadText').style.display = "none";

          var radius = 4;
          var dragging = false;
          var timer = 30;
          document.getElementById("demo").innerHTML = timer;
          let timerId = setInterval(function(){ timer--; document.getElementById("demo").innerHTML = timer; console.log(timer);}, 1000);
          setTimeout(() => { clearInterval(timerId); document.getElementById("demo").innerHTML = "";}, 30000);

          canvas.width = window.innerWidth;
          canvas.height = window.innerHeight;

          context.lineWidth = radius*2;

          var putPoint = function(e){
            if(dragging){
              context.lineTo(e.offsetX, e.offsetY);
              context.stroke();
              context.beginPath();
              //context.arc(e.clientX, e.clientY, radius, 0, Math.PI*2);
              context.arc(e.offsetX, e.offsetY, radius, 0, Math.PI*2);
              context.fill();
              context.beginPath();
              context.moveTo(e.offsetX, e.offsetY);
            }
          }

          var engage = function(e){
            dragging = true;
            putPoint(e);
          }

          var disengage = function(){
            dragging = false;
            context.beginPath();
          }

          canvas.addEventListener('mousedown', engage);
          canvas.addEventListener('mousemove',putPoint);
          canvas.addEventListener('mouseup', disengage);
        }

        canvas.addEventListener('click', start);

    </script>
  </body>
</html>
