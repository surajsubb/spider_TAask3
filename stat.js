function random(mn, mx) {   
    return Math.floor(Math.random() * (mx - mn)) + mn;  
}
var Data = JSON.parse(document.getElementById("hide1").innerHTML);
//alert(Data[1].itemname)

var myCanvas = document.getElementById("canvas_bar");
myCanvas.width = 300;
myCanvas.height = 320;
   
var ctx = myCanvas.getContext("2d");

var pieCanvas  = document.getElementById("canvas_pie");
pieCanvas.width = 500;
pieCanvas.height = 500;

var ctx_pie = pieCanvas.getContext("2d");
 
function drawLine(ctx, startX, startY, endX, endY,color){
    ctx.save();
    ctx.strokeStyle = color;
    ctx.beginPath();
    ctx.moveTo(startX,startY);
    ctx.lineTo(endX,endY);
    ctx.stroke();
    ctx.restore();
}
 
function drawBar(ctx, upperLeftCornerX, upperLeftCornerY, width, height,color){
    ctx.save();
    ctx.fillStyle=color;
    ctx.fillRect(upperLeftCornerX,upperLeftCornerY,width,height);
    ctx.restore();
}

function drawPie(ctx, x, y,start, end, radius, color){
    ctx.fillStyle=color;
    ctx.strokeStyle = color;
    ctx.lineWidth = 100;
    ctx.beginPath();
    ctx.arc(x,y,radius,start,end,false);
    ctx.stroke();
    ctx.closePath();
}

// BAR GRAPH
var Barchart = function(options){
    this.options = options;
    this.canvas = options.canvas;
    this.ctx = this.canvas.getContext("2d");
    this.colors = options.colors;
  
    this.draw = function(){
        var maxValue = 0;
        for (var i=0; i < this.options.data.length; i++){
            if(options.data[i].quantity > maxValue){
                maxValue = options.data[i].quantity;
            }
        }
        var canvasActualHeight = this.canvas.height - 20 - this.options.padding * 2;
        var canvasActualWidth = this.canvas.width - this.options.padding * 2;
 
        //drawing the grid lines
        var gridValue = 0;
        while (gridValue <= maxValue){
            var gridY = (canvasActualHeight) * (1 - gridValue/maxValue) + this.options.padding;
            drawLine(
                this.ctx,
                0,
                gridY,
                this.canvas.width,
                gridY,
                this.options.gridColor
            );
             
            //writing grid markers
            this.ctx.save();
            this.ctx.fillStyle = this.options.gridColor;
            this.ctx.textBaseline="bottom"; 
            this.ctx.font = "bold 10px Arial";
            this.ctx.fillText(gridValue, 10,gridY - 2);
            this.ctx.restore();
 
            gridValue+=this.options.gridScale;
        }      
  
        //drawing the bars
        var barIndex = 0;
        var numberOfBars = this.options.data.length;
        var barSize = (canvasActualWidth)/numberOfBars;
 
        for (var i=0; i < this.options.data.length; i++){
            var val = this.options.data[i].quantity;
            var barHeight = Math.round( (canvasActualHeight) * val/maxValue) ;
            drawBar(
                this.ctx,
                this.options.padding + barIndex * barSize,
                this.canvas.height-20 - barHeight - this.options.padding,
                barSize,
                barHeight,
                this.colors[barIndex%this.colors.length]
            );
 
            barIndex++;
        }
 
        //drawing series name
        this.ctx.save();
        this.ctx.textBaseline="bottom";
        this.ctx.textAlign="center";
        this.ctx.fillStyle = "white";
        this.ctx.font = "bold 20px Arial";
        this.ctx.fillText(this.options.seriesName, this.canvas.width/2,this.canvas.height-10);
        this.ctx.restore();  
         
        //draw legend
        barIndex = 0;
        var legend = document.querySelector("legend[for='canvas_bar']");
        var ul = document.createElement("ul");
        legend.append(ul);
        for (var i=0; i < this.options.data.length; i++){
            var li = document.createElement("li");
            li.style.color = "white";
            li.style.margin = "0 auto";
            li.style.marginTop = "10px";
            li.style.marginBottom = "10px";
            li.style.width = "100px";
            li.style.listStyle = "none";
            li.style.borderLeft = "20px solid "+this.colors[i];
            li.style.padding = "5px";
            li.style.textAlign = "left";
            li.textContent = this.options.data[i].itemname+": " + this.options.data[i].quantity;
            ul.append(li);
            barIndex++;
        }
    }
}

//PIE CHART 
var Piechart = function(options){
    
    
    this.options = options;
    this.canvas = options.canvas;
    this.ctx = this.canvas.getContext("2d");
    this.colors = options.colors;
  
    this.Alert = function(){
        alert("hello world");
    }
    this.draw = function(){
        var totalVal = [];
        var percent = [];
        percent[0]=0;
        var revenue = 0;
        for (var i=0; i < this.options.data.length; i++){
            totalVal[i] = options.data[i].price*options.data[i].quantity;
            revenue+=totalVal[i];
        }
        for (var i=0; i < this.options.data.length; i++){
            percent[i+1]=Math.PI*2*(totalVal[i]/revenue)+percent[i];
        }
        start = 0;
        end = 0;
        for (var i=0; i < this.options.data.length; i++){
            drawPie(this.ctx, 250, 250,percent[i], percent[i+1], 150, this.colors[i]);  
        }

        //drawing series name
        this.ctx.save();
        this.ctx.textBaseline="bottom";
        this.ctx.textAlign="center";
        this.ctx.fillStyle = "#000000";
        this.ctx.fillStyle = "white";
        this.ctx.font = "bold 18px Arial";
        this.ctx.fillText(this.options.seriesName, this.canvas.width/2,this.canvas.height/2+10);
        this.ctx.fillText("Total Revenue: ₹"+revenue, this.canvas.width/2,this.canvas.height);
        this.ctx.restore();  
         
        //draw legend
        barIndex = 0;
        var legend = document.querySelector("legend[for='canvas_pie']");
        var ul = document.createElement("ul");
        legend.append(ul);
        for (var i=0; i < this.options.data.length; i++){
            var li = document.createElement("li");
            li.style.color = "white";
            li.style.margin = "0 auto";
            li.style.marginTop = "10px";
            li.style.marginBottom = "10px";
            li.style.width = "150px";
            li.style.listStyle = "none";
            li.style.borderLeft = "20px solid "+this.colors[i];
            li.style.padding = "5px";
            li.style.textAlign = "left";
            li.textContent = this.options.data[i].itemname + ": ₹" + (this.options.data[i].quantity*this.options.data[i].price);
            ul.append(li);
            barIndex++;
            
        }
    }
}


color = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', 
        '#f58231', '#911eb4', '#46f0f0', '#f032e6', 
        '#bcf60c', '#fabebe', '#008080', '#e6beff', 
        '#9a6324', '#fffac8', '#800000', '#aaffc3', 
        '#808000', '#ffd8b1', '#000075', '#808080', 
        '#ffffff', '#000000'];
/*for(i=0;i<Data.length;i++){
 color.push("rgb("+random(100,255)+","+random(100,255)+","+random(100,255));
}*/
 var myBarchart = new Barchart(
    {
        canvas:myCanvas,
        seriesName:"Quantity Sold",
        padding:30,
        gridScale:2,
        gridColor:"#eeeeee",
        data:Data,
        colors: color
    }
);
myBarchart.draw();
var myPiechart = new Piechart(
    {
        canvas:pieCanvas,
        seriesName:"Revenue Split",
        data:Data,
        colors: color
    }
);
myPiechart.draw();