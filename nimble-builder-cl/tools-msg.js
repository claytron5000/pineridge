    ////////////////////////////////////////////////////////////
    // 2009.03.19
        
      var msg = 
        {
        //interface
        onmessage : false,   //*set this to recive message replies*
        onactive : false,  //be notified when we begin talking again (after idle)
        onidle : false,   //be notified when we're switching to idle.
        channel : 'url-here', //myprocessor.php
        
        send : function(str)
          {
          //put message to send queue
          this.out.push(str);
          
          if(this.xhr)
            {//we're in a post.. we'll just que the msg
            }//if
          else
            {//ok to send
            
            if(this.onactive)
              this.onactive();
            
             this.sendQueue();
            }//else
          
          },//func
        
        
        // ** 
        //process
        // - only uses one xhr at a time
        xhr : false, //will be false when idle.
        out : [],
        
        sendQueue : function()
          {//only call if its tuesday... just kidding.
          var send; 
          
          if(this.xhr) return false; // that's wierd.
          
          send = toJson(this.out);
          
          //new one
          this.xhr = this.xhrHTTPReqObj();
          
          this.xhr.onreadystatechange = this.stateChangeE;   
          this.xhr.open('POST',this.channel,true);
          this.xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
          send = "data="+encodeURIComponent(send);
          this.xhr.send(send);
       
          this.out = [];//clear to send later 
           
          },
        
        stateChangeE : function()
         {
          var xhr = msg.xhr; //global access becasue were're in the window object.
         
          var state = xhr.readyState;
          var reply;
          var ok;
          
          var i,o;
          
          if(state == 4)
            {
            reply = xhr.responseText;
            
            ok = true;
            try
              {
              reply = json2obj(reply);
              }
            catch(e)
              {
               ok = false;
              }
            
              if(ok)
               {
               if(reply.length > 0)
                 {
                 
                 for(i =0;i<reply.length;i++)
                   {
                   o = reply[i];
                   if(msg.onmessage)
                     msg.onmessage(o);    
                   }//send message replies
                   
                 }//if
               }//if
             
               
             msg.xhr = false;//xhr done
             
             //send if more to send
             if(msg.out.length > 0)
               msg.sendQueue();
             else
               {//phew... all done for a while
                 if(msg.onidle)
                   msg.onidle();
               }//else
                      
            }//if
         
         
         },//function
         
    xhrHTTPReqObj :
     function()
      {
      var xhr;
      if(window.ActiveXObject)
          xhr = new ActiveXObject("Microsoft.XMLHTTP");
        else
          xhr = new XMLHttpRequest();
          
      return xhr;
      },//func 
         
        
        ignorethis : 1 
        }
    
      ////////////////////////////////////////////////////////////      
      ////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////
      
//       function test()
//       {
//       msg.postMessage("allo!")
//       }
//       
//       function receiver(data)
//       {
//       console.log("recv: " , data);
//       }
//       
//       onload = function()
//       {
//       msg.channel = "process-post.php";
//       msg.onmessage = receiver;
//       }
   
    
    
 