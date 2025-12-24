// API Helper Functions
const API_BASE = '../backend';

// Make API call
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(`${API_BASE}/${endpoint}`, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, message: 'Network error' };
    }
}

// Upload file
async function uploadFile(file, type) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);
    
    try {
        const response = await fetch(`${API_BASE}/upload/upload-image.php`, {
            method: 'POST',
            body: formData
        });
        return await response.json();
    } catch (error) {
        console.error('Upload Error:', error);
        return { success: false, message: 'Upload failed' };
    }
}

// Get current user from localStorage
function getCurrentUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

// Save user to localStorage
function saveUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
}

// Check if user is logged in
function checkAuth(requiredType = null) {
    const user = getCurrentUser();
    
    if (!user) {
        window.location.href = 'login.html';
        return false;
    }
    
    if (requiredType && user.type !== requiredType) {
        window.location.href = user.type === 'company' ? 'company-home.html' : 'passenger-home.html';
        return false;
    }
    
    return user;
}

// Logout
function logout() {
    localStorage.removeItem('user');
    window.location.href = 'login.html';
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Format time
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// Format currency
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

// Show notification
function showNotification(message, type = 'info') {
    // Simple alert for now - can be enhanced with better UI
    alert(message);
}
