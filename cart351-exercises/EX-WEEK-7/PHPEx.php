<?php
//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sampleDataAsIfInAFile = array("orange","blue","red","green","yellow","purple","brown","grey","black","white");
    $sampleDataAsIfInAFile2 = array(30,40,50,60,70,80);
    $sampleDataAsIfInAFile3 = array(10,20,30,40,50,60,70,80);
// need to process -> we could save this data ...
 $xPos = $_POST['xpos'];
 $yPos = $_POST['ypos'];
 $x2Pos = $_POST['x2pos'];
 $y2Pos = $_POST['y2pos'];
 $color = $_POST['color'];
 $color2 = $_POST['color2'];
 $action = $_POST['action'];
 //do some silly processing:
 $newPos = $xPos+$yPos+$xPos2+$yPos2;
 // more silly processing:

 //lets choose a word from our "data file" based on the sum of the x and y pos...
 //there are 2 possible actions choose the word depending on action...
 if($action =="theButton"){
   $dataToSend =$sampleDataAsIfInAFile[$newPos%count($sampleDataAsIfInAFile)];
 }
 else if($action =="theRect1"){
   $dataToSend =$sampleDataAsIfInAFile2[$newPos%count($sampleDataAsIfInAFile2)];;
 }
 else if($action =="theRect2"){
   $dataToSend =$sampleDataAsIfInAFile3[$newPos%count($sampleDataAsIfInAFile3)];;
 }

    //package the data and echo back...
    $myPackagedData=new stdClass();
    $myPackagedData->word = $dataToSend;
     // Now we want to JSON encode these values to send them to $.ajax success.
    $myJSONObj = json_encode($myPackagedData);
    echo $myJSONObj;
    exit;
}//POST
?>

<!DOCTYPE html>
<html>
<head>
<title>USING JQUERY AND AJAX AND CANVAS </title>
<!-- get JQUERY -->
  <script src = "libs/jquery-3.3.1.min.js"></script>
<style>
body{
  margin:0;
  padding:0;
}
canvas{
  background:black;
  margin:0;
  padding:0;
}
#b{
  background:purple;
  color:white;
  margin:5px;
  text-align: center;
  padding: 5px;
  width:10%;
}
</style>
</head>
<body>
<div id = "b"><p>CLICK BUTTON</p></div>

<canvas id="myCanvas" width=500 height=500></canvas>
<!-- here we put our JQUERY -->
<script>
$(document).ready (function(){
  //declare some global vars ...
  let x =10;
  let x2 = 70;
  let y =10;
  let y2 = 100;
  let vx = 2;
  let vy = 3;
  let vx2 = 3;
  let vy2 = 2;
  let size = 50;
  let size2 = 50;
  let sColor = getRandomColor();
  let sColor2 = getRandomColor();
  let theWord = "white";
  let theWord2 = 50;
  let theWord3 = 0;
  //start ani
  goAni();
  // when we click on the canvas somewhere and the collision detection returns true ...

  $('#myCanvas').on("mousedown",function(event){
  //  console.log("mouseover on canvas");
    let truth = checkCollision(event);
    if(truth ===true){
      //our function for sending data
      sendData("theRect1");
    }
    console.log(event.clientX,event.clientY);
    let truth2 = checkCollision2(event);
    if(truth2 ===true){
      //our function for sending data
      sendData("theRect2");
    }
  });
  // if we click on the button other stuff happens ...
    $( "#b" ).click(function( event ) {
      //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
       event.preventDefault();
       console.log("button clicked");
       sendData("theButton");

     });

     function sendData(typeOfClick){
       let data = new FormData();
       data.append('action', typeOfClick);
       data.append('xpos', x);
       data.append('ypos', y);
       data.append('x2pos', x2);
       data.append('y2pos', y2);
       data.append('vx', vx);
       data.append('vy', vy);
       data.append('vx2', vx2);
       data.append('vy2', vy2);
       data.append('color', sColor);
       data.append('color2', sColor2);


       $.ajax({
             type: "POST",
             enctype: 'multipart/form-data',
             url: "PHPEx.php",
             data: data,
             processData: false,//prevents from converting into a query string
             contentType: false,
             cache: false,
             timeout: 600000,
             success: function (response) {
             //reponse is a STRING (not a JavaScript object -> so we need to convert)
             console.log(response);
             //use the JSON .parse function to convert the JSON string into a Javascript object
             let parsedJSON = JSON.parse(response);
             console.log(parsedJSON);
             if(typeOfClick ==="theButton"){
             theWord = parsedJSON.word;
           }
           else if(typeOfClick ==="theRect1"){
              theWord2 = parsedJSON.word;
           }
           else if(typeOfClick ==="theRect2"){
              theWord3 = parsedJSON.word;
           }

         },
         error:function(){
           console.log("error occured");
         }
       });
     } //end sendData

    function goAni(){
      let canvas = document.getElementById('myCanvas');
      let canvasContext = canvas.getContext('2d');

      let Rect2 = new square2(x2,y2,size2,size2,sColor2);

      function square2(x,y,w,h,c){
        this.x = x;
        this.y = y;
        this.w = w;
        this.h = h;
        this.c = c;
        this.angle = 0;

        this.update = function() {
            canvasContext.save();
            canvasContext.translate(this.x, this.y);
            canvasContext.rotate(this.angle);
            canvasContext.fillStyle = this.c;
            canvasContext.fillRect(this.w / -2, this.h / -2, this.w, this.h);
            canvasContext.restore();
        }
      }


      requestAnimationFrame(runAni);

      function runAni(){
         //need to reset the background :)
         // clear the canvas ...
         canvasContext.clearRect(0, 0, canvas.width, canvas.height);
         canvasContext.fillStyle = theWord;
         canvasContext.fillRect(0, 0, canvas.width, canvas.height);
         canvasContext.fillStyle = sColor;
         canvasContext.fillRect(x,y,theWord2,theWord2);

         // canvasContext.beginPath();
         // canvasContext.arc(x2,y2,50,0,2*Math.PI);

         Rect2.update();
         Rect2.angle = theWord3* Math.PI / 180;


         //canvasContext.stroke();
         // canvasContext.fillStyle = "#FFFFFF";
         // canvasContext.fillRect(x,y,1,1);
         x+=vx;
         y+=vy;
         if(x+theWord2>=canvas.width || x<=0){
           vx = -vx;
           sColor = getRandomColor();
         }
         if(y+theWord2>=canvas.height || y<=0){
           vy = -vy;
           sColor = getRandomColor();
         }
         requestAnimationFrame(runAni);
      }
  }
  function checkCollision(event){
    let domRect = document.getElementById("myCanvas").getBoundingClientRect();
     if(x>event.clientX-theWord2 && x<event.clientX+theWord2 && y >(event.clientY-domRect.top)-theWord2 && y<((event.clientY-domRect.top)+theWord2))
    {
      return true;
    }
    return false;
  }

  function checkCollision2(event){
    let domCirc = document.getElementById("myCanvas").getBoundingClientRect();
      if(x2>event.clientX-50 && x2<event.clientX+50 && y2 >(event.clientY-domCirc.top)-50 && y2<((event.clientY-domCirc.top)+50))
    {
      console.log("click");
      return true;
    }
    return false;
  }

  function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

}); //document ready
</script>
</body>
</html>
