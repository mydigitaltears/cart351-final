var c;
var t = 0;
var gameStart = false;
var timer = 30;

function setup() {
  c = createCanvas(windowWidth-20,windowHeight-20);
  imageMode(CENTER);
  // var x = (windowWidth - width) / 2;
  // var y = (windowHeight - height) / 2;
  // c.position(x, y);
  background(255);
  frameRate(60);
}

function draw() {
  if(gameStart){
    if(frameCount%60 == 0){
      if(timer > 0){
        fill(255);
        noStroke();
        rectMode(CENTER);
        rect(30,25,20,20);
        fill(0);
        textAlign(CENTER);
        text(timer, 30, 30);
        timer --;
      }
      else if(timer == 0){
        timer = 10;
        fill(255);
        noStroke();
        rectMode(CENTER);
        rect(30,25,20,20);
        saveC();
        gameStart = false;
      }
    }
    stroke(0);
    strokeWeight(4);
    if (mouseIsPressed === true) {
      line(mouseX, mouseY, pmouseX, pmouseY);
    }
  }
  else{
    background(255);
    showIntro();
  }
}

function saveC() {
  //saveCanvas(c, 'myCanvas', 'png');
  createImage(width, height);
}

function showIntro(){
  fill(0);
  textAlign(CENTER);
  textSize(30);
  text('draw a monkey, you have 30 seconds', width/2, height/2);
  text('click when ready', width/2, height/1.5);
  if(mouseIsPressed){
    gameStart = true;
    background(255);
  }
}
