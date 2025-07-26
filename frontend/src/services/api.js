import axios from 'axios';     

// creating axious instance with base configuration 
// axious is a library that allows us to make http requests
// we are creating an instance of axious with a base url and a timeout
// the base url is the url of our backend
// the timeout is the time after which the request will be cancelled
// we are also adding a header to our request
// the header is the authorization header
// the authorization header is used to send the token to the backend
// the token is used to authenticate the user


const api = axios.create({
    baseURL: 'http://localhost:8000',
    timeout: 1000,
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
});

api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
)
// explanation of this code : 
// we are creating an interceptor for our requests
// an interceptor is a function that is called before a request is made
// we are using the request interceptor to add the token to the header of our request
// we are using the response interceptor to check if the response is ok
// if the response is not ok, we are redirecting the user to the login page
// we are also using the response interceptor to check if the response is unauthorized
// if the response is unauthorized, we are redirecting the user to the login page

api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
)


export default api;
