const RoomId = (window.location.href).split('/')[3];
var IsConnecting = false;

window.addEventListener('beforeunload', function(event) {
    if(!IsConnecting){
        fetch('/room/api/disconnect', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'room='+RoomId+'&name='+"_"
        });
    }
  });