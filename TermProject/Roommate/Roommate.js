$(document).ready(function() {
  var urlParams = new URLSearchParams(window.location.search);
  var roommateId = urlParams.get('id');
  var index = parseInt(roommateId, 10);

  console.log("Roommate ID:", roommateId);
  fetch('Roommate.json')
  .then(response => response.json())
  .then(data => {
    // Access a specific item using dot notation
    const currentRoommate = data.items[index]; 
    console.log(currentRoommate.img);
    document.getElementsByClassName('image')[0].innerHTML = 
    '<img src="' + currentRoommate.img + '" alt="Roommate Image">';

    document.getElementsByClassName('hobbies')[0].innerHTML = 
    '<h4> About me </h4><p>' + currentRoommate.description + '</p>';
    document.getElementById('name').innerHTML = currentRoommate.Name +
    '<span class="material-symbols-outlined" id = "favorite">favorite</span><span class="material-symbols-outlined" id = "message">chat_bubble</span>';
  
  })
});