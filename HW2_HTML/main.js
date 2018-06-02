//set width & height of to width and height of window
var canvas = document.querySelector("canvas");
canvas.width = window.innerWidth ;
canvas.height = 500;

var c = canvas.getContext('2d');

var grd = c.createLinearGradient(canvas.width/2, canvas.height/2, 5,
            canvas.width/2, canvas.height/2, 300);
grd.addColorStop(0, "#003333");
grd.addColorStop(1, "#336666");


c.fillStyle = grd;
c.fillRect(0, 0, canvas.width, canvas.height);



function Circle(x, y, Dx, Dy, radius)
{
  this.xpos = x;
  this.ypos = y;
  this.dx = Dx;
  this.dy = Dy;
  this.radius = radius;
  
  this.draw = function()
  {
    c.beginPath();
    c.arc(this.xpos, this.ypos, this.radius, 0,
          Math.PI * 2, false);
    c.strokeStyle = 'blue';
    c.stroke();
  }
  
  this.update = function()
  {
    if(this.xpos + radius >= canvas.width|| this.xpos - radius <= 0)
      {
        this.dx = -this.dx;
      }
    if(this.ypos + radius >= canvas.height || this.ypos - radius <= 0)
      {
        this.dy = -this.dy;
      }
    
    this.xpos += this.dx;
    this.ypos += this.dy;
  }
  
} //end circle class

var circleArray = [];
var numCircles = 100;

 for(var i = 10; i < numCircles; i++)
    {
      var circleRadius = 50;
      var circleX = Math.random() * (canvas.width - 2 * circleRadius);
      var circleY = Math.random() * (canvas.height - 2 * circleRadius);
      var circleDx = (Math.random() - 0.5);
      var circleDy = (Math.random() - 0.5);
      
      circleArray.push(new Circle(circleX, circleY,circleDx, circleDy, circleRadius) );
    }



var loopCounter = 0;

function animate()
{
  loopCounter += 1;
  if(loopCounter <= 500){
  requestAnimationFrame(animate);
   }
  //c.clearRect(0, 0, innerWidth, innerHeight);
  //update the circles
  for(var i = 0; i < circleArray.length; i++)
  {
    circleArray[i].update();
  }
  
  //check if intersecting
  for(var i = 0; i < circleArray.length; i++)
  {
    for(var j = 0; j < i; j++)
      {
        var distanceBetweenCircles = Math.sqrt(
          Math.pow(circleArray[i].xpos - circleArray[j].xpos, 2) 
        + Math.pow(circleArray[i].ypos - circleArray[j].ypos, 2));
        
        //if the distance between the centers of the 2 circles is less than
        //the sum of their radii, then the 2 circles are intersecting. draw
        //a line between their centers.
        if(distanceBetweenCircles <= (circleArray[i].radius + circleArray[j].radius))
          {
          
            c.beginPath();
            c.moveTo(circleArray[i].xpos, circleArray[i].ypos);
            c.lineTo(circleArray[j].xpos, circleArray[j].ypos);
            c.strokeStyle = 'rgb(255,255,255,.01)'
            c.stroke();
          }
      }
  }
}

animate();

console.log("end");