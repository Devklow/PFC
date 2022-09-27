const Winner = document.getElementById('Winner');
const Round = document.getElementById('RoundText');
const RockButton = document.getElementById('rock');
const PaperButton = document.getElementById('paper');
const ScissorsButton = document.getElementById('scissors');
const ReplayButton = document.getElementById('ReplayButton');

var PlayedCard = 0;

RockButton.addEventListener("click", ()=>{
    PlayCard(1);
});

PaperButton.addEventListener("click", ()=>{
    PlayCard(2);
});

ScissorsButton.addEventListener("click", ()=>{
    PlayCard(3);
});

ReplayButton.addEventListener("click", ()=>{
    Play();
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
    document.getElementById('rock').disabled = false;
    document.getElementById('paper').disabled = false;
    document.getElementById('scissors').disabled = false;
}

function HideCard(){
    document.getElementById('rock').classList.add('d-none');
    document.getElementById('paper').classList.add('d-none');
    document.getElementById('scissors').classList.add('d-none');
    document.getElementById('rock').disabled = true;
    document.getElementById('paper').disabled = true;
    document.getElementById('scissors').disabled = true;
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
function Play(){
    document.getElementById('PCunknow').classList.add('d-none');
    document.getElementById('PCrock').classList.add('d-none');
    document.getElementById('PCpaper').classList.add('d-none');
    document.getElementById('PCscissors').classList.add('d-none');
    ReplayButton.disabled = true;
    ReplayButton.classList.add("d-none");
    Winner.innerHTML = "Select a sign"
    ShowCard();
    Round.innerHTML="⚪";
};

function PlayCard(value){
    PlayedCard = value;
    ShowCurrentCard();
    ComputerCard = Math.floor(Math.random() * 3)+1;
    console.log(ComputerCard);
    ReplayButton.classList.remove("d-none");
    if(ComputerCard == PlayedCard){
        Winner.innerHTML = "Draw ! 🏳️";
        Round.innerHTML="⚫";
    }
    else if((ComputerCard+PlayedCard)%2==1){
        if(PlayedCard > ComputerCard){
            Winner.innerHTML = "You Win ! 🏆";
            Round.innerHTML="🟢";
        }
        else{
            Winner.innerHTML = "You Lose ! 👎";
            Round.innerHTML="🔴";
        }
    }
    else{
        if(PlayedCard < ComputerCard){
            Winner.innerHTML = "You Win ! 🏆";
            Round.innerHTML="🟢";
        }
        else{
            Winner.innerHTML = "You Lose ! 👎";
            Round.innerHTML="🔴";
        }
    }
    if(ComputerCard==1){
        document.getElementById('PCrock').classList.remove('d-none');
    }
    if(ComputerCard==2){
        document.getElementById('PCpaper').classList.remove('d-none');
    }
    if(ComputerCard==3){
        document.getElementById('PCscissors').classList.remove('d-none');
    }
    document.getElementById('PCunknow').classList.add('d-none');
    ReplayButton.textContent = "Replay";
    ReplayButton.disabled = false;
};
