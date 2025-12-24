/**
 * Authentication Utilities
 * Shared functions for handling user authentication state
 */


const Auth = {

    apiPath: '/FlightBookingWebsite/backend/auth',


    getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    },


    isLoggedIn() {
        return this.getUser() !== null;
    },


    getUserType() {
        const user = this.getUser();
        return user ? user.type : null;
    },


    async logout() {
        try {
            await fetch(this.apiPath + '/logout.php');
        } catch (e) {
            console.error('Logout error:', e);
        }
        localStorage.removeItem('user');
        window.location.href = '../../backend/auth/login.php';
    },


    async checkSession() {
        try {
            const response = await fetch(this.apiPath + '/check-session.php');
            const data = await response.json();

            if (!data.loggedIn) {
                localStorage.removeItem('user');
                return false;
            }


            localStorage.setItem('user', JSON.stringify(data.user));
            return true;
        } catch (e) {
            console.error('Session check error:', e);
            return false;
        }
    },


    async requireAuth(allowedTypes = null) {
        const loggedIn = await this.checkSession();

        if (!loggedIn) {
            window.location.href = '../../backend/auth/login.php';
            return false;
        }

        if (allowedTypes && !allowedTypes.includes(this.getUserType())) {
            alert('Access denied. You do not have permission to view this page.');
            window.location.href = this.getUserType() === 'company'
                ? 'pages/company-pages/companyhome.php'
                : 'pages/user-pages/userhome.php';
            return false;
        }

        return true;
    },


    updateUserDisplay() {
        const user = this.getUser();
        if (!user) return;


        const nameElements = document.querySelectorAll('[data-user-name]');
        nameElements.forEach(el => el.textContent = user.name);

        const emailElements = document.querySelectorAll('[data-user-email]');
        emailElements.forEach(el => el.textContent = user.email);


        document.querySelectorAll('[data-show-logged-in]').forEach(el => {
            el.style.display = 'block';
        });

        document.querySelectorAll('[data-hide-logged-in]').forEach(el => {
            el.style.display = 'none';
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (Auth.isLoggedIn()) {
        Auth.updateUserDisplay();
    }
});


function handleLogin(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim().toLowerCase();
            const password = document.getElementById('password').value;
            const submitBtn = e.target.querySelector('button[type="submit"]');

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';
            console.log('Sending login request for:', email);

            fetch("backend/auth/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Store user info in localStorage
                    const user = {
                        email: email,
                        role: data.role,
                        name: data.name || ''
                    };
                    localStorage.setItem('user', JSON.stringify(user));
                    alert(data.message);
                    
                    // Redirect based on role
                    const redirectUrl = data.role === 'company' 
                        ? 'frontend/pages/company-pages/companyhome.php' 
                        : 'frontend/pages/user-pages/userhome.php';
                    window.location.href = redirectUrl;
                } else {
                    alert(data.message || 'Login failed');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Login';
                }
            })
            .catch(err => {
                console.error(err);
                alert("Server error. Please try again.");
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
            });

                }


