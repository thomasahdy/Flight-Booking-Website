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
