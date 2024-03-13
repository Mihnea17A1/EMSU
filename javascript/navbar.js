document.getElementById('logout').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to log out?')) {
        // Redirect to log out page or handle log out logic here
        window.location.href = 'logout.php'; // Replace with your logout URL
    }
});

document.getElementById('back').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Esti sigur ca vrei sa te intorci?')) {
        // Redirect to log out page or handle log out logic here
        window.location.href = 'home_page.php'; // Replace with your logout URL
    }
});

document.getElementById('back_to_dot_list').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Esti sigur ca vrei sa te intorci?')) {
        // Redirect to log out page or handle log out logic here
        window.location.href = 'lista_dotari.php'; // Replace with your logout URL
    }
});

