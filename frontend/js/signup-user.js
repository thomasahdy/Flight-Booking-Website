function submitPassengerForm(event) {
    event.preventDefault();

    const photo = document.querySelector('input[name="photo"]').files[0];
    const passportImg = document.querySelector('input[name="passportImg"]').files[0];

    if (!photo || !passportImg) {
        alert("Please upload both photo and passport image");
        return;
    }

    const formData = new FormData();
    formData.append('photo', photo);
    formData.append('passportImg', passportImg);

    fetch('../../backend/auth/register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration successful!');
            window.location.href = '../user-pages/userhome.php';
        } else {
            alert(data.message || 'Registration failed');
            window.location.href = '../user-pages/userhome.php';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = '../user-pages/userhome.php';
    });
}
