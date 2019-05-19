
      var zauber = document.getElementById('divzauber');
      zauber.addEventListener('mousemove', drag);
      zauber.addEventListener('mouseup', dragstop);
      
            
      var zauber1 = null;
      
      var dragx = 0;
      var dragy = 0;
      
      var posx = 0;
      var posy = 0;
      
      
      function dragstart(zauberdiv)
      {
        zauber1 = zauberdiv;
        dragx = posx - zauber1.offsetLeft;
        dragy = posy - zauber1.offsetTop;
        
        
      }
     
      
      
      
       function dragstop()
      {
        zauber1 = null;
      }
      
      function drag(ereignis)
      {
        posx = document.all ? window.event.clientX : ereignis.pageX;
        posy = document.all ? window.event.clientY : ereignis.pageY;
        
        if(zauber1 != null)
        {
        /*alert("zauber1="+zauber1+"<br>"+"posx="+posx+"<br>"+"dragx="+dragx);*/
        zauber1.style.left = (posx - dragx) + "px";
        zauber1.style.top = (posy - dragy) + "px";
        }
      }

    