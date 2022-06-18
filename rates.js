var backStars = document.querySelector(".star-rating .back-stars");

backStars.addEventListener("click", function(e){
  let fillPercent = getFillPercent(e, this);
  fillPercent = (fillPercent > 100) ? 100 : (fillPercent < 0) ? 0 : fillPercent;
  
  fillPercent = fillPercent + "%" ;
  const frontStars = document.querySelector(".front-stars");
  
  if(!frontStars.className.includes("fill"))
    frontStars.className+= " fill";  
  
  frontStars.style.width = fillPercent;
  
 document.querySelector("#rate-number").value = Number(fillPercent.slice(0,-1))/20;
});

backStars.addEventListener("mousemove", function(e){
  const fillPercent = getFillPercent(e, this) + "%";
  const frontStars = document.querySelector(".front-stars");
  
  if(!frontStars.className.includes("over"))
    frontStars.className += " over";
  
  frontStars.style.width = fillPercent;
});

backStars.addEventListener("mouseenter", function(e){
  var frontStars = document.querySelector(".front-stars");
  if(frontStars.className.includes("over"))
    frontStars.className.replace("over","");
  
});

backStars.addEventListener("mouseleave", function(){
  const frontStars = document.querySelector(".front-stars");
  const number =  document.querySelector("#rate-number");
  frontStars.style.width = number || 0;
  
  if(frontStars.className.indexOf("over") > -1)
     frontStars.className.concat(" over");
});

function getFillPercent(page, element){
  const clickedOffset = page.pageX - element.offsetLeft;
  const starsContainerWidth = parseInt(window.getComputedStyle(element).width.replace("px",""));
  const fillPercent = Math.floor((clickedOffset * 100) / starsContainerWidth);
  return fillPercent;
}
var submitrate=document.querySelector(".submitrate");
var rate=document.querySelector("#rate-number");

submitrate.addEventListener("click", function(event){
  if(rate.value=="")
  {
    event.preventDefault();
  }

});