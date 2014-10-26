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