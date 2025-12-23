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
        window.location.href = '/FlightBookingWebsite/frontend/login.html';
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
            window.location.href = '/FlightBookingWebsite/frontend/login.html';
            return false;
        }

        if (allowedTypes && !allowedTypes.includes(this.getUserType())) {
            alert('Access denied. You do not have permission to view this page.');
            window.location.href = this.getUserType() === 'company'
                ? '/FlightBookingWebsite/frontend/company-home.html'
                : '/FlightBookingWebsite/frontend/passenger-home.html';
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


async function handleLogin(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim().toLowerCase();
            const password = document.getElementById('password').value;
            const submitBtn = e.target.querySelector('button[type="submit"]');

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';

            try {
                console.log('Sending login request for:', email);
                
                const response = await fetch('../backend/auth/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                console.log('Response status:', response.status, response.statusText);
                
                // Check if response is OK
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response error:', errorText);
                    try {
                        const errorData = JSON.parse(errorText);
                        alert('Login failed: ' + (errorData.message || errorText));
                    } catch (e) {
                        alert('Login failed: ' + errorText);
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Sign in';
                    return;
                }

                const responseText = await response.text();
                console.log('Response text:', responseText);
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    console.error('Response was:', responseText);
                    alert('Server returned invalid response. Check console for details.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Sign in';
                    return;
                }

                console.log('Parsed response data:', data);

                if (data.success) {
                    // Store user data in localStorage for frontend use
                    localStorage.setItem('user', JSON.stringify(data.user));
                    console.log('Login successful, redirecting...');
                    // Redirect based on user type
                    window.location.href = data.redirect;
                } else {
                    // Show detailed error message
                    let errorMsg = data.message || 'Login failed. Please try again.';
                    if (data.error) {
                        console.error('Server error:', data.error);
                        errorMsg += '\n\nError details: ' + data.error;
                    }
                    alert(errorMsg);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Sign in';
                }
            } catch (error) {
                console.error('Login error:', error);
                console.error('Error stack:', error.stack);
                alert('Network error occurred. Please check:\n1. XAMPP MySQL is running\n2. Apache is running\n3. Check browser console for details');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Sign in';
            }
        }