import api from './api';

export const  authService = {
    // register new user 
    register: async(userData) => {
        try{
            const response = await api.post('/auth/register', userData); // we are sending a post request to the backend with the user data
            
            if(response.data.token){
                localStorage.setItem('authToken', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));
            }
            return response.data;

        }catch(error){
            throw error.response.data || {error: 'Registration failed'};
        }
    },
    // login user
    login: async(userData) => {
        try{
            const response = await api.post('/auth/login', userData);
            if(response.data.token){
                localStorage.setItem('authToken', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));
            }
            return response.data;
        }catch(error){
            throw error.response.data || {error: 'Login failed'};
        }
    },
    // logout user
    logout: async() => {
        try{
            await api.post('/auth/logout');
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');
        }catch(error){
            throw error.response.data || {error: 'Logout failed'};
        }
    }, 

    // get current user info 
    getCurrentUser: async() => {
        try{
            const response = await api.get('/auth/me');
            return response.data;
        }catch(error){
            throw error.response.data || {error: 'Get current user failed'};
        }
    },

    // check if user is authenticated
    isAuthenticated: () => {
        return !!localStorage.getItem('authToken');
    }, 

    // get  stored user data 
    getUser: () =>{
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }
}