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

     // $img = $_POST['img'];
     // $img = str_replace('data:image/png;base64,', '', $img);
     // $img = str_replace(' ', '+', $img);
     // $fileData = base64_decode($img);
     // //saving
     // $fileName = 'photo.png';
     // file_put_contents($fileName, $fileData);

     //$image = $_POST['an_image'];
     if($_FILES)
      {
        //echo "file name: ".$_FILES['filename']['name'] . "<br />";
        //echo "path to file uploaded: ".$_FILES['filename']['tmp_name']. "<br />";
       //$fname = $_FILES['filename']['name'];
       //move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);
       $fname = $_FILES['filename']['name'];

       $countRowsStatement= "SELECT COUNT (*) FROM artCollection";
       $countRows = $db->query($countRowsStatement);
       $countResult = $countRows->fetchArray(SQLITE3_NUM);
       echo $countResult[0]."<br \>";
       if($countResult[0] == 0)
        {
         $nameID = 1;
         echo " we have an empty database:: <br \>";
        }
       else{
         $id = 'SELECT * FROM artCollection';
         $result = $db->query($id);

         while($row = $result->fetchArray(SQLITE3_ASSOC))
         {
          echo "<div class ='articleS'>";
          echo "<div class ='entry-header'>";
          // go through each column in this row
          // retrieve key entry pairs
          foreach ($row as $key=>$entry)
          {
            if($key=="pieceID"){
              $nameID = $entry+1;
            }
          }
         }//end while
       }

       move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$nameID);
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
    	$imageWithPath= "images/".$nameID;
      // for the new column
      $time = date("Y-m-d",time());
      $queryInsert ="INSERT INTO artCollection(artist, title, geoLoc, creationDate, image)VALUES ('$artist_es', '$title_es','$creationDate_es','$loc_es','$imageWithPath')";
      //$queryInsert ="INSERT INTO artCollection(image)VALUES ('$image_es')";
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
      #formContainer {
        display: none;
        position: relative;
      }
    </style>
  </head>
    <body>
      <div id="onloadText">
        <h1>Draw a monkey, you have 30 seconds</h1>
        <h1>Click to start!</h1>
      </div>
      <p id="demo"></p>
      <div id= "formContainer">
        <!--form done using more current tags... -->
        <form id="insertGallery" action="" enctype ="multipart/form-data">
        <!-- group the related elements in a form -->
        <h3> SUBMIT AN ART WORK :</h3>
        <fieldset>
        <p><label>Artist:</label><input type="text" size="24" maxlength = "40" name = "a_name" required></p>
        <p><label>Title:</label><input type = "text" size="24" maxlength = "40"  name = "a_title" required></p>
        <p><label>Geographic Location:</label><input type = "text" size="24" maxlength = "40" name = "a_geo_loc" required></p>
        <p><label>Creation Date (DD-MM-YYYY):</label><input type="date" name="a_date" required></p>
        <!--<p><label>Upload Image:</label> <input type ="file" name = 'filename' size=10 ></p>-->
        <p class = "sub"><input type = "submit" name = "submit" value = "submit my info" id ="buttonS" /></p>
         </fieldset>
        </form>
      </div>
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
              // let timerId = setInterval(function(){start();},10);
              // setTimeout(() => {clearInterval(timerId);},5000);
              drawing();
          }
        };
      })();

      function drawing () {
            let ended = false;
            document.getElementById('onloadText').style.display = "none";
            var radius = 4;
            var dragging = false;
            let timer = 30;
            document.getElementById("demo").innerHTML = timer;
            let timerId = setInterval(function(){ timer--; document.getElementById("demo").innerHTML = timer; console.log(timer);}, 1000);
            setTimeout(() => { clearInterval(timerId); document.getElementById("demo").innerHTML = ""; saveImage();stop(); ended = true; console.log(ended);}, 30000);

            function stop() {
              canvas.removeEventListener('mousedown', engage);
              canvas.removeEventListener('mousemove',putPoint);
              canvas.removeEventListener('mouseup', disengage);
              document.getElementById('canvas').style.position = "relative";
              //document.getElementById('canvas').style.display = "none";
            }

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


        } // end of drawing

        let saveImage = function(ev) {


          document.getElementById('formContainer').style.display = "inline";


          $('#canvas')[0].toBlob((blob) => {
             let URLObj = window.URL || window.webkitURL;
             let a = document.createElement("a");
             a.href = URLObj.createObjectURL(blob);
             //a.download = "untitled.png";
             document.body.appendChild(a);
             // a.click();
             document.body.removeChild(a);

          //var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream")
          // window.location.href=image; // it will save locally
          $(document).ready (function(){
              var pngUrl = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream"); // PNG is the default
              //window.location.href=pngUrl; // it will save locally
              $("#insertGallery").submit(function(event) {
               //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
               event.preventDefault();
               console.log("button clicked");
               let form = $('#insertGallery')[0];
               let data = new FormData(form);
               data.append("filename",blob);

               $.ajax({
                      type: "POST",
                      enctype: 'multipart/form-data',
                      url: "play.php",
                      data: data,
                      processData: false,//prevents from converting into a query string
                      contentType: false,
                      cache: false,
                      timeout: 600000,
                      success: function (response) {
                      //reponse is a STRING (not a JavaScript object -> so we need to convert)
                      console.log(response);
                    //use the JSON .parse function to convert the JSON string into a Javascript object
                    // let parsedJSON = JSON.parse(response);
                    // console.log(parsedJSON);
                    // displayResponse(parsedJSON);
                     },
                     error:function(){
                    console.log("error occurred");
                  }
                });

                window.location.href = 'viewResults.php';
             });
             // validate and process form here
          });

        });//blob
        } // end of saveImage()

        canvas.addEventListener('click', start);

    </script>
  </body>
</html>
