import axios from "axios";

const axiosClient = axios.create({
    baseURL: `http://127.0.0.1:8000/api`,
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    
});

axiosClient.interceptors.request.use(config => {
    const token = localStorage.getItem('ACCESS-TOKEN');
    config.headers.Authorization = token ? `Bearer ${token}` : '';
    return config;
})


axiosClient.interceptors.response.use(response => { return response }, error => 
{
    const { response } = error;
    if (response && response.status === 401) {
        localStorage.removeItem('ACCESS-TOKEN');
        window.location.href = '/login';
    }

    throw error;
}
);


export default axiosClient;
