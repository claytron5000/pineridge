buildercl_logout_link = "/./?logout";

var buildercl =
  {
  root : '.',
  init : function()
    {//call after page dom has loaded
    var l = this.getlist();
    var o,i,h;
    
    msg.channel = this.root+'/process-messages.php';
    msg.onmessage = buildercl.receiver;
    
    for(i=0;i<l.length;i++)
      {
      o = l[i];
      h = o.innerHTML;
      o.buildercl = 
        {
        value : h
        }
      
      }//for
      
    this.tick();
    
    //o=document.createElement('div');
    
    o = document.getElementsByTagName('body')[0];
    h = document.createElement('div');
    h.className = 'buldercl-logout-link';
    h.style.position = 'fixed';
    h.style.width = '100%';
    h.style.bottom = '0px';
    //h.style.right = '15px';
    h.style.padding = '5px'
    h.style.paddingLeft = '10px'
    h.style.backgroundColor = 'white';
    h.style.border = 'solid 1px #777';
    h.style.zIndex = '9999';
    h.innerHTML = "Editing: <a href='"+this.root+buildercl_logout_link+"'>Click to Logout</a>";
    o.appendChild(h);
    

    
     
      
    },//func
    
  getlist : function()
    {
    return getElementsByClass('editable');
    },
    
  deltacheck : function()
    {
    var l = this.getlist();
    
    var o,i,h,n,cl,m;
    
    for(i=0;i<l.length;i++)
      {
      o = l[i];
      cl = o.buildercl;
      h = cl.value
      n = o.innerHTML;
      
      if(h != n)
        {
        o.buildercl.value = n
        m = {
           t : o.id,
           v : n
            }//json
        msg.send(m)
        }
      
      
      }//for
    
    
    },
    
  tick : function()
    {
    
    buildercl.deltacheck();
    
    setTimeout('buildercl.tick()',2000);
    },  
    
  receiver : function(m)
    {
    //log("saving ["+m.path+"] '"+m.data+"'");
    //console.log(m)
    }
    
  }//var