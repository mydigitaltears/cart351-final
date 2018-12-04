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
  $sql_delete="DELETE FROM artCollection WHERE artist = 'Martha'";
  $ok1 = $db ->exec($sql_delete);
  if (!$ok1) die("Cannot execute statement.");
  echo "DELETE OPERATION OF table called artCollection successful";
   // $db = new MyDB();
   // $sql_update="UPDATE artCollection SET title = 'Untitled' WHERE pieceID =1";
   // // again we do error checking when we try to execute our SQL statements on the db
   // $ok1 = $db ->exec($sql_update);
   // if (!$ok1) die("Cannot execute statement.");
   // // if we reach this point then all the data has been updated successfully.
   // echo "UPDATE OF table called artCollection successful";
}
catch(Exception $e)
{
   die($e);
}

?>
