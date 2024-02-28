// intialize first slider image in HTMLcollection array
let flag=0;
// get the elements which have slide class
let slides=document.getElementsByClassName('slide');

// create function for displaying on by one slider image
function slideShow(num){
    
    for(let x of slides)
    {
       x.style.display='none';
    }

    slides[num].style.display='block';

}
// crate a function to control next and prev arrow
function controller(x){
   flag=flag+x;
   if(flag<0)
   {
    flag=slides.length-1;
   }
   if(flag>slides.length-1)
   {
    flag=0;
   }
   slideShow(flag);
}

// create setInterval function to call the controller in given time which automatic change slider image
setInterval(function controller(){
   let x=1;
   flag=flag+x;
   if(flag>slides.length-1)
   {
    flag=0;
   }
   //console.log(flag);
   slideShow(flag);
},4000);

