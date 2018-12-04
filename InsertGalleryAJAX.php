<?php

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
    // $myPackagedData=new stdClass();
    // $myPackagedData->artist = $artist ;
    // $myPackagedData->title = $title ;
    // $myPackagedData->location = $loc ;
    // $myPackagedData->description = $description ;
    // $myPackagedData->creation_Date = $creationDate ;
    // $myPackagedData->fileName = $fname ;
    //  // Now we want to JSON encode these values to send them to $.ajax success.
    // $myJSONObj = json_encode($myPackagedData);
    // echo $myJSONObj;
    // exit;

  }//FILES
}//POST
?>
<!DOCTYPE html>
<html>
<head>
<title>Sample Insert into Gallery Form USING JQUERY AND AJAX </title>
<!-- get JQUERY -->
  <script src = "js/libraries/jquery-3.3.1.min.js"></script>
<!--set some style properties::: -->
<link rel="stylesheet" type="text/css" href="css/galleryStyle.css">
</head>
<body>
  <!-- NEW for the result -->
<div id = "result"></div>

<div class= "formContainer">
<!--form done using more current tags... -->
<form id="insertGallery" action="" enctype ="multipart/form-data">
<!-- group the related elements in a form -->
<h3> SUBMIT AN ART WORK :::</h3>
<fieldset>
<p><label>Artist:</label><input type="text" size="24" maxlength = "40" name = "a_name" required></p>
<p><label>Title:</label><input type = "text" size="24" maxlength = "40"  name = "a_title" required></p>
<p><label>Geographic Location:</label><input type = "text" size="24" maxlength = "40" name = "a_geo_loc" required></p>
<p><label>Creation Date (DD-MM-YYYY):</label><input type="date" name="a_date" required></p>
<p><label>Upload Image:</label> <input type ="file" name = 'filename' size=10 required/></p>
<p class = "sub"><input type = "submit" name = "submit" value = "submit my info" id ="buttonS" /></p>
 </fieldset>
</form>
</div>
<script>
$(document).ready (function(){
    $("#insertGallery").submit(function(event) {
       //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
      event.preventDefault();
     console.log("button clicked");
     let form = $('#insertGallery')[0];
     let data = new FormData(form);
     $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "insertGalleryAJAX.php",
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
   });
   // validate and process form here
    // function displayResponse(theResult){
    //   let container = $('<div>').addClass("outer");
    //   let title = $('<h3>');
    //   $(title).text("Results from user");
    //   $(title).appendTo(container);
    //   let contentContainer = $('<div>').addClass("content");
    //   for (let property in theResult) {
    //     console.log(property);
    //     if(property ==="fileName"){
    //       let img = $("<img>");
    //       $(img).attr('src','images/'+theResult[property]);
    //
    //       $(img).appendTo(contentContainer);
    //     }
    //     else{
    //       let para = $('<p>');
    //       $(para).text(property+"::" +theResult[property]);
    //         $(para).appendTo(contentContainer);
    //     }
    //
    //   }
    //   $(contentContainer).appendTo(container);
    //   $(container).appendTo("#result");
    // }

});
</script>
</body>
</html>
