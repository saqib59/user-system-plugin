
var app = require("express")();
var bodyParser = require('body-parser');
var http = require('http').Server(app);
var io = require("socket.io")(http);
var mysql = require("mysql");
var dateFormat = require('dateformat');
   

app.use(require("express").static('data'));
app.use(bodyParser.urlencoded({
  extended: true
}));
app.use(bodyParser.json());

app.get("/",function(req,res){
    //res.sendFile(__dirname + '../index.php');
   // res.sendFile('http://localhost/american-academy-live/chat-room/');
});

//conncecting nodejs to remote mysql
var con = mysql.createConnection({
 connectionLimit :   100,
  host : 'localhost',
  user : 'syd_has',
  password : 'T@O_-MZzh8wi',
  database : 'effects'
});
con.connect();


//  getting today's date  
var now = new Date();
var today=dateFormat(now, "mmmm dS, yyyy");

// This is auto initiated event when Client connects to Your Machine.  
io.on('connection',function(socket){  
    console.log("hello");
  // authenticating  and gettting user name from wp_usermeta table. 
  socket.on('validate',function(data){

console.log("there");
    //socket.emit('validate message',{user_id:data[0], receiver_id: data[1]});
   
    var query="select meta_value from wp_usermeta where meta_key = 'nickname' and user_id = '" + data[1] + "'";
    con.query(String(query),function(err,rows){
      if(rows.length>0){
        
      if(data[2] == 'administrator'){
        var get_message= "SELECT * FROM user_system_messaging WHERE sender_id ="+data[0]+" OR sender_id = 1001 AND receiver_id=1001 OR receiver_id ="+data[0]+" ORDER BY id ASC";
      }
      else{
         var get_message= "SELECT * FROM user_system_messaging WHERE sender_id ="+data[1]+" OR sender_id = 1001 AND receiver_id=1001 OR receiver_id ="+data[1]+" ORDER BY id ASC";
      }
     
        //Getting all the messages 
        //var get_message="select * from user_system_messaging";
        con.query(String(get_message),function(err,get_message_rows){
            
            // saving username in socket object 
            socket.nickname=rows[0].meta_value;

            //sending response to client side code.  
            io.emit('user entrance',{
              info:rows[0].meta_value+" is online.",
              message:get_message_rows
            }); 
        });

      }
    });
 
  });
    
  //inserting messages to tables and sending the messages to client side code. 
  socket.on('send msg',function(data){
    //var queryy="insert into user_system_messaging values ('','"+data.msg+"','"+data.sender_id+"','"+data.receiver_id+"','1')";
    var queryy = "INSERT INTO `user_system_messaging`(`message`,`sender_id`,`receiver_id`,`new_message_status`,`status`) values ('"+data.msg+"',"+data.sender_id+","+data.receiver_id+","+1+","+1+")";
   // var queryy = "INSERT INTO `user_system_messaging`(`message`,`sender_id`,`receiver_id`,`new_message_status`) values ('"+sdsd+"',"+1001+","+25+","+1+")";


    con.query(String(queryy),function(err,rows){
      if(err == null){
        io.emit('get msg',{

          error : 'no error',
          user:socket.nickname,
          message:data.msg,
          sender_id:data.sender_id,
          receiver_id:data.receiver_id
        });
      }
      else{
        io.emit('get msg',{

          error : 'There is an error for inserting data',
          user:socket.nickname,
          message:data.msg,
          sender_id:data.sender_id,
          receiver_id:data.receiver_id
        });
      };        

    });
    


    /*if(data.sender_id == 1001){
      io.emit('get msg',{
        user:socket.nickname,
        message:data.msg,
        sender_id:data.sender_id,
        receiver_id:data.receiver_id
      });
    }
    else{
      io.emit('get msg',{
        user:socket.nickname,
        message:data.msg,
        sender_id:data.sender_id,
        receiver_id:data.receiver_id
      });
    }*/
    
  });



  /***  User Is Typing ***/
  // when the client emits 'typing', we broadcast it to others
  /*socket.on('typing', () => {

    socket.broadcast.emit('typing', {
      username: socket.nickname
    });
  

  });*/
  socket.on('typing', function(data){

    /*socket.broadcast.emit('typing', {
      username: data
    });*/

    socket.broadcast.emit('typing', {
      username: socket.nickname
    });
  

  });
  


  // when the client emits 'stop typing', we broadcast it to others
  socket.on('stop typing', () => {

    socket.broadcast.emit('stop typing', {
      username: socket.nickname
    });
  
  });



  //When user dissconnects from server.
  socket.on('disconnect',function(){

    io.emit('exit',{message:socket.nickname});
  });

});


http.listen(9001,function(){
    // console.log(blog.template_directory);
    console.log("Listening on 81");
});