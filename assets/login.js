import ApiService from './common/ApiService.js';
import './styles/login.css';

function sendDataToServer(data) {
  ApiService.post(ApiService.getApiBaseUrl() + 'login/facebook', data, (result) => {
    console.log("Success:", result.data);
    if (result.result === 'ok')
      window.location.href = '/';
    else
      window.alert(result.message);
  },
  (error) => {
    console.error(error);
    window.alert(error);
  });
}

function statusChangeCallback(response) {
    console.log(response);
    if (response.status === 'connected') {
      sendDataToServer(response.authResponse);
    }
}

window.fbAsyncInit = function() {
  FB.init({
    appId      : '916840026018060',
    cookie     : true,
    xfbml      : true,
    version    : 'v16.0'
  });

  FB.AppEvents.logPageView();   
  document.dispatchEvent(new Event('fbloaded'));
};

function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}
window.checkLoginState = checkLoginState;

document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('fbloaded', () => {
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
    var finished_rendering = function() {
      console.log("finished rendering plugins");
      var spinner = document.getElementById("spinner");
      spinner.remove();
    }
    FB.Event.subscribe('xfbml.render', finished_rendering);
  });
});