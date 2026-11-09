
// Mobile Menu
function showMenu() {
    document.getElementById("menu").classList.toggle("show");
}

// Booking Search
function searchRooms(event) {
    event.preventDefault();

    let checkin = document.getElementById("checkin").value;
    let checkout = document.getElementById("checkout").value;

    if (checkout <= checkin) {
        alert("Please select a valid checkout date!");
        return;
    }

    alert("Your booking dates are valid!");
}

function bookRoom(event) {
    event.preventDefault();

    let checkin = document.getElementById("checkin").value;
    let checkout = document.getElementById("checkout").value;

    if (checkout <= checkin) {
        alert("Please select a valid checkout date!");
        return;
    }

    alert("Booking details submitted successfully!");
}
