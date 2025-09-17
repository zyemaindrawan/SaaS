import axios from 'axios';

window.axios = axios;

// Simple route helper function
window.route = function(name, params = {}) {
    const routes = {
        'dashboard': '/dashboard',
        'profile.edit': '/profile',
        'logout': '/logout',
        'login': '/login',
        'register': '/register',
        'password.request': '/forgot-password',
        'password.email': '/forgot-password',
        'password.confirm': '/confirm-password',
        'password.store': '/reset-password',
        'password.update': '/password',
        'profile.update': '/profile',
        'profile.destroy': '/profile',
        'verification.send': '/email/verification-notification',
    };
    
    return routes[name] || '#';
};

// Simple current route helper
window.route.current = function(name) {
    const currentPath = window.location.pathname;
    const routes = {
        'dashboard': '/dashboard',
    };
    
    return currentPath === routes[name];
};

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
