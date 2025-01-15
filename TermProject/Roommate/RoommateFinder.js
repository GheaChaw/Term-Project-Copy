function getRoommate(roommateField){
  fetch('roommateDisplay.php')
  .then(response => response.json())
  .then(roommates => {
      console.log("This is from database!");
      console.log("test");
      console.log(roommates);
      
      let listItems = "";

      roommates.forEach(person =>{
          listItems += `<li><a href="../room/room.html" id = "${person.id}">
          <img src= "Listing1.jpg" width="230" height="230" alt="House Picture"> 
          <p class = "imageDesc">${person.preferred_gender}</p></a></li>`;
      });

      card.innerHTML = listItems;

  })
  .catch(error => {
      card.innerHTML = `<li><p>Error loading roommates: ${error.message}</p></li>`;
  });
}

$(document).ready(function() {
  $("#filterCard").hide()
    $.ajax({
      type: "GET",
      url: "Roommate.json",
      dataType: "json",
      success: function(responseData, status){
        console.log(responseData)
        var output = " <div class = 'box'>";

        $.each(responseData.items, function(i, item) {
          var list_item = '<a href="javascript:f(\'' + item.id + '\');">';
          list_item += '<div class="roommate" id="' + item.id + '">';
          list_item += '<img src="' + item.img + '" class="user_photo"></img>';
         
          list_item+='<h4>'+item.Name +"</h4>";
          list_item+='<h3>Age: '+item.age +"</h3>"
          list_item+='</div> </a>';
          
          output+=list_item;
        });
        output+="</div>";
  
        $('#card').html(output);

      }, error: function(msg) {
              // there was a problem
              alert("There was a problem: " + msg.status + " " + msg.statusText);
      }
    });
    
    const filterButton = document.getElementById("roommateFilter");
    const filterForm = document.getElementById("formFilter");
    const filterClose = document.getElementById("xButton");

    filterButton.addEventListener("click", function() {
      if (filterForm.style.display === "none") {
          filterForm.style.display = "flex";   
          } 
      else{
          //hiding it by clicking the filter button
          filterForm.style.display = "none";  
      }
    });

  //when the close button is clicked on the filter form, it hides the selection
    filterClose.addEventListener("click",function(){
      if(filterForm.style.display === "flex") {
          filterForm.style.display = "none";
      }
    })
});
function f(id){
  window.id = id;
  console.log(window.id);
  window.open("Roommate.html?id=" + id, "_self"); 
}
