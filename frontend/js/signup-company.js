function submitCompanyForm(event) {
    event.preventDefault();

    const company_name = document.querySelector('input[name="company_name"]').value.trim();
    const username = document.querySelector('input[name="username"]').value.trim();
    const bio = document.querySelector('textarea[name="bio"]').value.trim();
    const address = document.querySelector('input[name="address"]').value.trim();
    const location = document.querySelector('input[name="location"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value;
    const tel = document.querySelector('input[name="tel"]').value.trim();
    const logoImg = document.querySelector('input[name="logoImg"]').files[0];

    if (!company_name || !username || !bio || !address || !email || !password || !tel || !logoImg) {
        alert("Please fill all required fields");
        return;
    }

    const formData = new FormData();
    formData.append('company_name', company_name);
    formData.append('username', username);
    formData.append('bio', bio);
    formData.append('address', address);
    formData.append('location', location);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('tel', tel);
    formData.append('logoImg', logoImg);

    fetch('../../backend/auth/register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration successful!');
            window.location.href = '../company-pages/companyhome.php';
        } else {
            alert(data.message || 'Registration failed');
            window.location.href = '../company-pages/companyhome.php';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = '../company-pages/companyhome.php';
    });
}
