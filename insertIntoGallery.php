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
   echo ("Opened or created imageGallery data base successfully<br \>");
   $countRowsStatement= "SELECT COUNT (*) FROM artCollection";
   $countRows = $db->query($countRowsStatement);
   if (!$countRows) die("Cannot execute query.");
   $countResult = $countRows->fetchArray(SQLITE3_NUM);
   echo $countResult[0]."<br \>";
   if($countResult[0] == 0)
    {
     echo " we have an empty database:: <br \>";
    }
    else
    {
        echo "We already have " .$countResult[0] . " rows in the artCollection Table";
    }

    $queryArray = array(
      "INSERT INTO artCollection (title, artist, creationDate, geoLoc, image) VALUES ('A Monkey', 'Xavier','2018-12-04','Montreal','images/myCanvas.png')",
 	    "INSERT INTO artCollection (title, artist, creationDate, geoLoc, image) VALUES ('A Fish', 'Xavier','2018-12-04','Montreal','images/myCanvas2.png')",
 	    "INSERT INTO artCollection (title, artist, creationDate, geoLoc, image) VALUES ('An Elephant', 'Xavier', '2018-12-04','Montreal','images/myCanvas3.png')",
  	  "INSERT INTO artCollection (title, artist, creationDate, geoLoc, image) VALUES ('A Spider', 'Xavier','2018-12-04','Montreal','images/myCanvas4.png')",
  	 );
     // go through each entry in the array and execute the INSERT query statement....
     for($i =0; $i< count($queryArray); $i++)
     {
 	     $ok1 = $db->exec($queryArray[$i]);
 	      if (!$ok1)
 	      {
 		       die($db->lastErrorMsg());
 	      }
     }
  // if we reach this point then all the data has been inserted successfully.
  echo "INSERTION OF ".count($queryArray)." entries into artCollection Table successful";
}
catch(Exception $e)
{
   die($e);
}
?>
