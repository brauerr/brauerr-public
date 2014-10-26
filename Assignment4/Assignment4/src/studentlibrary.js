//Your library code goes in this file.

//Helper function to take key pairs of strings 
//and put in format to construct GET request
//Source:  
//http://stackoverflow.com/questions/111529/create-query-parameters-in-javascript
function encodeQueryData(data) {
  var ret = [],
    d;
  //wrap in hasOwnProperty to ensure only true object properties are added
  for (d in data) {
    if (data.hasOwnProperty(d)) {
      ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
    }
  }
  return ret.join('&');
}

function Weather(city, state, getWind, getMaxT, getMinT, getCurrentT) {
  this.city = city;
  this.state = state;
  this.getWind = getWind;
  this.getMaxT = getMaxT;
  this.getMinT = getMinT;
  this.getCurrentT = getCurrentT;
}

function saveWeatherSettings() {
  var city = document.getElementsByName('city_name')[0].value,
    stateSelect = document.getElementById('state_select'),
    state = stateSelect.options[stateSelect.selectedIndex].value,
    getWind = document.getElementsByName('Wind')[0].checked,
    getMaxT = document.getElementsByName('Max_Temp')[0].checked,
    getMinT = document.getElementsByName('Min_Temp')[0].checked,
    getCurrentT = document.getElementsByName('Current_Temp')[0].checked,
    weatherSettings = new Weather(city, state, getWind, getMaxT, getMinT, getCurrentT);
    
    localStorage.setItem('weatherSettings', JSON.stringify(weatherSettings));
}

function getWeather() {
  var city = document.getElementsByName('city_name')[0].value,
    stateSelect = document.getElementById('state_select'),
    state = stateSelect.options[stateSelect.selectedIndex].value,
    getWind = document.getElementsByName('Wind')[0].checked,
    getMaxT = document.getElementsByName('Max_Temp')[0].checked,
    getMinT = document.getElementsByName('Min_Temp')[0].checked,
    getCurrentT = document.getElementsByName('Current_Temp')[0].checked,
    weatherSettings = new Weather(city, state, getWind, getMaxT, getMinT, getCurrentT),
    
    
}

window.onload = function () {
  settings = localStorage.getItem('weatherSettings');
  if (settings === null) {
    settings = new Weather('','OR',true,true,true,true);
    localStorage.setItem('weatherSettings', JSON.stringify(settings));
  } else {
    settings = JSON.parse(localStorage.getItem('weatherSettings'));
  }
  applySettings(settings);
};

function applySettings(settings) {
  document.getElementById('state_select').value = settings.state;
  document.getElementsByName('city_name')[0].value = settings.city;
  document.getElementsByName('Wind')[0].checked = settings.getWind;
  document.getElementsByName('Max_Temp')[0].checked = settings.getMaxT;
  document.getElementsByName('Min_Temp')[0].checked = settings.getMinT;
  document.getElementsByName('Current_Temp')[0].checked = settings.getCurrentT;
}



function ajaxRequest(URL, Type, Parameters) {
  //URL is base URL the request will be made to
  //type will be string 'POST' or string 'GET'
  //if POST send as 'application/x-www-form-urlencoded' 
  //(set content type in the header)
  //if GET then create proper URL string and use within function
  //parameters will be obejct containing key value pairs of strings 
  //{'name': 'John', 'thing':'foo'};

  var success,
    myCode,
    myCodeDetail,
    myResponse,
    getURL,
    myRequest = new XMLHttpRequest(), //for IE may not support earlier than 10
    processMyRequest = function() {
      if (myRequest.readyState === 4) {
        if (myRequest.status === 200) {
          success = true;
        } else {
          success = false;
        }
        myCode = this.status;
        myCodeDetail = this.statusText;
        myResponse = this.responseText;
      }
    };

  if (!myRequest) {
    throw 'Unable to create HttpRequest.';
  }

  if (Type === 'GET') {
    getURL = URL + '?' + encodeQueryData(Parameters);
    myRequest.onreadystatechange = processMyRequest;
    myRequest.open('GET', getURL, false);
    myRequest.send();
  } else if (Type === 'POST') {
    myRequest.onreadystatechange = processMyRequest;
    myRequest.open('POST', URL, false);
    myRequest.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded');
    //set up as header payload (same format as text after '?' in GET)
    myRequest.send(encodeQueryData(Parameters));
  } else {
    throw 'Type String not GET or POST';
  }

  //success true if response had a code corresponding with success
  //code holds html response code
  //codeDetail holds text associated with the particular html status code
  //response should be string representation of 
  //the response data received from the server  
  return {'success': success, 'code': myCode,
      'codeDetail': myCodeDetail, 'response': myResponse};
}



function localStorageExists() {
  //should return true if able to read and write from local storage
  //else return false
  localStorage.clear();
  var testString = 'testing123',
    settingsTest;

  localStorage.setItem('testLocalStorage', testString);

  //set to null if getItem() fails
  settingsTest = localStorage.getItem('testLocalStorage');
  if (settingsTest !== null) {
    return true;
  }
  return false;
}