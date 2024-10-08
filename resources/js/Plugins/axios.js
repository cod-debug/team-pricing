// axios.js
import Axios from 'axios';

export default {
  install: (app) => {
    // Set the base URL for your API if needed
    Axios.defaults.baseURL = '';
    
    let metaTag = document.querySelector('meta[name="user-token"]');
    let user_token = metaTag ? metaTag.getAttribute('content') : null;

    Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    Axios.defaults.headers.common['Authorization'] = `Bearer ${user_token}`;

    // Add Axios instance to the Vue app prototype
    app.config.globalProperties.$axios = Axios;
  },
};