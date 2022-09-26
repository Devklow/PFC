const HostName = document.getElementById('HostName');
const PlayerName = document.getElementById('PlayerName');
const MyName = document.getElementById('UserName').textContent;

const Winner = document.getElementById('Winner');
const RoomId = (window.location.href).split('/')[3];

const RockButton = document.getElementById('rock');
const PaperButton = document.getElementById('paper');
const ScissorsButton = document.getElementById('scissors');
const ApplyButton = document.getElementById('ApplyButton');
const ReadyButton = document.getElementById('ReadyButton');

var MyType = document.getElementById('UserType').textContent;
var Opponent =  "";
var ImReady = false;
var IsReady = false;
var HavePlayed = false;
var HePlayed = false;
var IsConnected = false;
var PlayedCard = 0;
var HisCard = 0;
var RoundList = "";
var CurrentRoundList = "";

RockButton.addEventListener("click", ()=>{
    Play(1);
});

PaperButton.addEventListener("click", ()=>{
    Play(2);
});

ScissorsButton.addEventListener("click", ()=>{
    Play(3);
});

ApplyButton.addEventListener("click", ()=>{
    UpdateRoom(document.getElementById('round').value,document.getElementById('visibility').value );
});

ReadyButton.addEventListener("click", ()=>{
    Ready();
    HidePlayedCard();
});

function HidePlayedCard(){
    document.getElementById('MCrock').classList.add('d-none');
    document.getElementById('MCpaper').classList.add('d-none');
    document.getElementById('MCscissors').classList.add('d-none');
}

function ShowCard(){
    document.getElementById('rock').classList.remove('d-none');
    document.getElementById('paper').classList.remove('d-none');
    document.getElementById('scissors').classList.remove('d-none');
}

function HideCard(){
    document.getElementById('rock').classList.add('d-none');
    document.getElementById('paper').classList.add('d-none');
    document.getElementById('scissors').classList.add('d-none');
}

function ShowCurrentCard(){
    HideCard();
    if(PlayedCard==1){
        document.getElementById('MCrock').classList.remove('d-none');
    }
    else if(PlayedCard==2){
        document.getElementById('MCpaper').classList.remove('d-none');
    }
    else if(PlayedCard==3){
        document.getElementById('MCscissors').classList.remove('d-none');
    }
}
function Ready(){
    fetch('/room/api/play', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'room='+RoomId+'&name='+MyName+'&ready=1'
    });
    ImReady = true;
    ReadyButton.disabled = true;
};


function Play(value){
    PlayedCard = value;
    HavePlayed=true;
    ShowCurrentCard();
    fetch('/room/api/play', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'room='+RoomId+'&name='+MyName+'&value='+value
    });
};

function UpdateRoom(round, visibility){
    fetch('/room/api/update', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'room='+RoomId+'&name='+MyName+'&round='+round+'&visibility='+visibility
    });
};

setInterval(function(){ 
    fetch('/room/api/get/'+RoomId)
    .then(res =>{
        if(res.ok){
            res.json().then(data => {
                if(data['Host']==MyName && MyType=="Player"){
                    MyType = "Host";
                    document.getElementById('UserType').innerHTML =MyType;
                    document.getElementById('parametersDropdown').classList.remove('d-none');
                }
                if((MyType=="Host")){
                    ImReady = data['HostReady'];
                    IsReady = data['PlayerReady'];
                    HePlayed = data['PP'];
                    HisCard = data['PC'];
                }
                if(MyType=="Player"){
                    ImReady = data['PlayerReady'];
                    IsReady = data['HostReady'];
                    HePlayed = data['HP'];
                    HisCard = data['HC'];
                }
                if(ImReady){
                    ReadyButton.classList.add('d-none')
                    ReadyButton.disabled = true;
                    if(!HavePlayed && IsReady){
                        ShowCard();
                    }
                    else{
                        HideCard();
                    }
                }
                if(data['CurrentRound']==0 || data['CurrentRound']==null){
                    Winner.innerHTML = "Click ready when you are";
                    RoundList = [];
                    for (i=0; i<data['MaxRound'];i++){
                        RoundList.push("⚪");
                    }
                    document.getElementById('RoundText').innerHTML=RoundList.toString().replaceAll(',', "");
                }
                if(data['Host']!=null && data['Player']!=null){
                    IsConnected = true;
                    if(IsReady && ImReady){
                        if(!HavePlayed){
                            ShowCard();
                            Winner.innerHTML = "Select a sign";
                            document.getElementById('Action').innerHTML = Opponent + " is selecting a sign...";
                        }
                    }
                    else if(!IsReady && ImReady){
                        document.getElementById('Action').innerHTML = Opponent + " is not ready";
                        Winner.innerHTML = "Waiting for other player";
                    }
                    else if(IsReady && !ImReady){
                        document.getElementById('Action').innerHTML = Opponent + " is ready";
                        Winner.innerHTML = "Waiting for you";
                    }
                    if(MyType=="Host"){
                        Opponent = data['Player']
                    }
                    else{
                        Opponent = data['Host']
                    }
                    if(ReadyButton.classList.contains('d-none') && !ImReady){
                        ReadyButton.classList.remove('d-none');
                        ReadyButton.disabled = false;
                        Winner.innerHTML = "Click ready when you are";
                        document.getElementById('Action').innerHTML = Opponent+" is not ready";
                    }
                }
                else{
                    IsConnected = false;
                    if(!ReadyButton.classList.contains('d-none')){
                        ReadyButton.classList.add('d-none')
                        ReadyButton.disabled = true;
                    }
                    document.getElementById('Action').innerHTML ="no one is connected";
                    Winner.innerHTML = "Waiting for someone to connect";
                    HidePlayedCard();
                }
                if(data['Winner']!=null){
                    if(HisCard==1){
                        document.getElementById('PCrock').classList.remove('d-none');
                    }
                    if(HisCard==2){
                        document.getElementById('PCpaper').classList.remove('d-none');
                    }
                    if(HisCard==3){
                        document.getElementById('PCscissors').classList.remove('d-none');
                    }
                    document.getElementById('PCunknow').classList.add('d-none');
                    if(data['Winner']=="Eq"){
                        RoundList[data['CurrentRound']-1] = "⚫";
                    }
                    else if(data['Winner']==MyName){
                        RoundList[data['CurrentRound']-1] = "🟢";
                    }
                    else{
                        RoundList[data['CurrentRound']-1] = "🔴";
                    }
                    document.getElementById('RoundText').innerHTML=RoundList.toString().replaceAll(',', "");
                }
                else{
                    document.getElementById('PCrock').classList.add('d-none');
                    document.getElementById('PCpaper').classList.add('d-none');
                    document.getElementById('PCscissors').classList.add('d-none');
                    document.getElementById('PCunknow').classList.remove('d-none');
                }
                if(data['MatchWinner']==null){
                    if(data['Winner']==MyName){
                        Winner.innerHTML = "You Win ! 🏆";
                        HavePlayed=false;
                    }
                    else if(data['Winner']==Opponent){
                        Winner.innerHTML = "You Lose ! 👎";
                        HavePlayed=false;
                    }
                    else if(data['Winner']=="Eq"){
                        Winner.innerHTML = "Draw ! 🏳️";
                        HavePlayed=false;
                    }
                }
                else{
                    if(data['MatchWinner']=="Eq"){
                        Winner.innerHTML = "There is no winner 🏳️";
                        HavePlayed=false;
                    }
                    else{
                        Winner.innerHTML = data['MatchWinner']+" won the match 🏆";
                        HavePlayed=false;
                    }
                }
                if((data['HP']==true && MyType=="Host" && data['PP']==false) || (data['PP']==true && MyType=="Player" && data['HP']==false)){
                    Winner.innerHTML = "Waiting for other player";
                }
                if((data['HP']==false && MyType=="Host" && data['PP']==true) || (data['PP']==false && MyType=="Player" && data['HP']==true)){
                    document.getElementById('Action').innerHTML = Opponent + " has played";
                }
            })
            
        }
        else[
            console.log("ERROR")
        ]
    })
}, 1000);

window.addEventListener('beforeunload', function(event) {
    fetch('/room/api/disconnect', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'room='+RoomId+'&name='+MyName
    });
  });

