<?php
  // put required html mark up
  echo"<html>\n";
  echo"<head>\n";
  echo"<title> Output from the image Gallery Database </title> \n";
  //include CSS Style Sheet
  echo "<link rel='stylesheet' type='text/css' href='css/main.css'>\n";
  echo"</head>\n";
  // start the body ...
  echo"<body>\n";
  // place body content here ...
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
       $sql_select='SELECT * FROM artCollection';
       $selectEntryOne="SELECT * FROM artCollection WHERE pieceID = 1";
       // the result set
       $result = $db->query($sql_select);
       if (!$result) die("Cannot execute query.");
       // fetch first row ONLY...
       $row = $result->fetchArray(SQLITE3_ASSOC);
       //print_r($row);
       $result->reset();
         echo"<nav id='main-head'>";
           echo"<nav id='main-title'>";
             echo"<ul class='head-title'>";
               echo"<li class='the-title'> <a href='viewResults.php' title=''>Drawnimo</a> </li>";
             echo"</ul>";
           echo"</nav>";
           echo"<nav id='main-nav-wrap'>";
             echo"<ul class='main-navigation sf-menu'>";
               echo"<li class='current'><a href='viewResults.php' title=''>Home</a></li>";
               echo"<li class='has-children'><a href='play.php' title=''>Play</a></li>";
             echo"</ul>";
           echo"</nav> <!-- end main-nav-wrap -->";
         echo"</nav>";
        echo"<div id='back'>";
        // get a row...
        while($row = $result->fetchArray(SQLITE3_ASSOC))
        {
         echo "<div class ='articleS'>";
         echo "<div class ='entry-header'>";
         // go through each column in this row
         // retrieve key entry pairs
         foreach ($row as $key=>$entry)
         {
           //if the column name is not 'image'
            if($key!="image" && $key!="pieceID")
            {
              if($key=="title"){
                echo "<p>".$entry."</p>";
              }
              else if($key=="artist"){
                echo "<p>By : ".$entry."</p>";
              }
              else {
                // echo the key and entry
                echo "<p>".$entry."</p>";
              }
            }
         }

        // put image in last
          echo "</div>";
          // access by key
          echo "<div class ='aImage'>";
            $imagePath = $row["image"];
            echo "<img src = $imagePath\>";
          echo "</div>";
          echo "</div>";
        }//end while
  echo"</div>";
    }

    catch(Exception $e)
    {
       die($e);
    }
  echo"</body>\n";
  echo"</html>\n";
?>
