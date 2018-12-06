<?php
  // This is the main page of my project, drawnimo. The idea is very simple, but
  // it has been a real challenge to work it out. In this page I just show the informations
  // inside my databse which are a name, a subject, a data and a location + the image that
  // the user drew. The user simply goes from this page into the Play page by clicking on the
  // Play link and the comes back on this page to see his/her artwork with all of the other pieces.
  // At first I wanted to create a more complex kind of organisation, but I had a really hard time
  // with sql at first so I decided to keep it simple and make it work. Most of the code will be in
  // the Play_Page.php because it is where the actual drawing program is located and all of the
  // database insertion. It could be better, but I am very happy with the result and my next objective
  // would be to make it work on mobile or create an account system with password encryption and everything.
  // I hope it isn't too much of a mess. I also putted all of my exercises into an exercise folder.

  // put required html mark up
  echo"<html>\n";
  echo"<head>\n";
  echo"<title> Drawnimo Main Page </title> \n";
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
      // importing the database in reverse order so the last results display first
       $db = new MyDB();
       $sql_select='SELECT * FROM artCollection ORDER BY pieceID DESC';
       $selectEntryOne="SELECT * FROM artCollection WHERE pieceID = 1";
       // the result set
       $result = $db->query($sql_select);
       if (!$result) die("Cannot execute query.");
       // fetch first row ONLY...
       $row = $result->fetchArray(SQLITE3_ASSOC);
       //print_r($row);
       $result->reset();
       // head html navigation bar in php, style is in css
         echo"<nav id='main-head'>";
           echo"<nav id='main-title'>";
             echo"<ul class='head-title'>";
               echo"<li class='the-title'> <a href='Main_Page.php' title=''>Drawnimo</a> </li>";
             echo"</ul>";
           echo"</nav>";
           echo"<nav id='main-nav-wrap'>";
             echo"<ul class='main-navigation sf-menu'>";
               echo"<li class='current'><a href='Main_Page.php' title=''>Home</a></li>";
               echo"<li class='has-children'><a href='Play_Page.php' title=''>Play</a></li>";
             echo"</ul>";
           echo"</nav> <!-- end main-nav-wrap -->";
         echo"</nav>";
        echo"<div id='back'>";
        // get a row...

        // basic db exporting, I basically just hide the pieceID
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
              if($key=="subject"){
                echo "<p><span>".$entry."</span></p>";
              }
              else if($key=="artist"){
                echo "<p><span>By : ".$entry."</span></p>";
              }
              else {
                // echo the key and entry
                echo "<p><span>".$entry."</span></p>";
              }
            }
         }

        // put image in last
          echo "</div>";
          // access by key
          echo "<div class ='aImage'>";
            $imagePath = $row["image"];
            echo "<img src = $imagePath>";
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
