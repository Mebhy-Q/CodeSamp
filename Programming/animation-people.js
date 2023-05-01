$(document).ready(function () {
  var hei = $(window).height() * 0.12
  var JK = "#dJ-hover";
  var LC = "#dL-hover";

  gsap.to(".click-lead", 0, {opacity:.5});
  gsap.to(".click-bo", 0, {opacity:.5});
  // click on a person
  $(".click").click(function(){    
      console.log("click")      
      gsap.to(window, {delay:.6, duration: 1, scrollTo:{y:"#collapse" + (this.id), offsetY:hei}});
      ga('send', 'event', 'click', 'People' , 'People - Person Open - '+(this.id) );
    }); 
    //Close Profle   
  $(".close").click(function(){
    $('.show').collapse('hide');
    gsap.to(window, {delay:.6, duration: 1, scrollTo:{y:'#top', offsetY:hei}});
    return false;
  });
  //Hover animation for clickable people
  $(".click").mouseenter(function(){    
    var target = "#d"+(this.id)+"-hover";   
      console.log(target);
      //differentiate between left side hover and right hover
      if(target== LC || target==JK){
        var xval = 30;
      }else{
        var xval = -30;
      }
      gsap.to(target, 0, {display:"block", x:xval});
      gsap.to(target, 0.3, {x:0});
      gsap.to(target, 0.7, {opacity:1});     
    })
    .mouseleave(function(){
      var target = "#d"+(this.id)+"-hover";
      console.log(target);
      //differentiate between left side hover and right hover
      if(target== LC || target==JK){
        var xval = 10;
      }else{
        var xval = -10;
      }
      gsap.to(target, 0.3, {x: xval});      
      gsap.to(target, 0.3, {opacity:0});
      gsap.to(target, 0, {delay:.5, display:"none"});      
    })
  });  