<?php

  class MyDB extends SQLite3
     {
        function __construct()
        {
           $this->open('"/db/graffitiGallery.db');
        }
     }

  try
  {
     $db = new MyDB();
     echo ("Opened or created graffiti gallery data base successfully<br \>");
     $theQuery = 'CREATE TABLE artCollection (pieceID INTEGER PRIMARY KEY NOT NULL, artist TEXT, title TEXT,geoLoc TEXT, creationDate TEXT,descript ,image TEXT)';
     $ok = $db ->exec($theQuery);
    	// make sure the query executed
    	if (!$ok)
    	die($db->lastErrorMsg());
    	// if everything executed error less we will arrive at this statement
    	echo "Table artCollection created successfully<br \>";
  }
  catch(Exception $e)
  {
     die($e);
  }
?>
