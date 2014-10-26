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
    weatherSettings = new Weather(city, state, getWind,
        getMaxT, getMinT, getCurrentT);

  localStorage.setItem('weatherSettings', JSON.stringify(weatherSettings));
}

function ajaxWeather(city, state) {
  var URL = 'http://api.openweathermap.org/data/2.5/weather?q=' + city +
      ',' + state + '&units=imperial',
    weatherRequest = new XMLHttpRequest(),
    requestResult,
    processWeatherRequest = function() {
      if (weatherRequest.readyState === 4) {
        requestResult = this.responseText;
      }
    };

  if (!weatherRequest) {
    throw 'Unable to create HttpRequest.';
  }

  weatherRequest.onreadystatechange = processWeatherRequest;
  weatherRequest.open('GET', URL, false);
  weatherRequest.send();

  return requestResult;
}

function displayWeather(weatherObject) {
  var outputWindSpeed = document.getElementById('output_Wind_Speed'),
    outputWindDir = document.getElementById('output_Wind_Dir'),
    outputCity = document.getElementById('output_City'),
    outputState = document.getElementById('output_State'),
    outputMaxTemp = document.getElementById('output_Max_Temp'),
    outputMinTemp = document.getElementById('output_Min_Temp'),
    outputCurrentTemp = document.getElementById('output_Current_Temp'),
    city = weatherObject.name,
    stateSelect = document.getElementById('state_select'),
    state = stateSelect.options[stateSelect.selectedIndex].value,
    windSpeed = weatherObject.wind.speed,
    windDir = weatherObject.wind.deg,
    maxT = weatherObject.main.temp_max,
    minT = weatherObject.main.temp_min,
    currentT = weatherObject.main.temp;

  outputCity.innerHTML = 'City: ' + city;
  outputState.innerHTML = 'State: ' + state;

  if (document.getElementsByName('Wind')[0].checked) {
    outputWindSpeed.innerHTML = 'Wind Speed (mph): ' + windSpeed +
        '   (m/s): ' + (windSpeed * 0.44704).toFixed(1);
    outputWindDir.innerHTML = 'Wind Direction (degrees): ' + windDir;
  } else {
    outputWindSpeed.innerHTML = '';
    outputWindDir.innerHTML = '';
  }

  if (document.getElementsByName('Max_Temp')[0].checked) {
    outputMaxTemp.innerHTML = 'Maximum Temperature (F): ' + maxT +
        '   (C): ' + ((maxT - 32) / 1.8).toFixed(1);
  } else {
    outputMaxTemp.innerHTML = '';
  }

  if (document.getElementsByName('Min_Temp')[0].checked) {
    outputMinTemp.innerHTML = 'Minimum Temperature (F): ' + minT +
        '   (C): ' + ((minT - 32) / 1.8).toFixed(1);
  } else {
    outputMinTemp.innerHTML = '';
  }

  if (document.getElementsByName('Current_Temp')[0].checked) {
    outputCurrentTemp.innerHTML = 'Current Temperature (F): ' + currentT +
        '   (C): ' + ((currentT - 32) / 1.8).toFixed(1);
  } else {
    outputCurrentTemp.innerHTML = '';
  }
}

function clearOutputData() {
  document.getElementById('output_City').innerHTML = '';
  document.getElementById('output_State').innerHTML = '';
  document.getElementById('output_Wind_Speed').innerHTML = '';
  document.getElementById('output_Wind_Dir').innerHTML = '';
  document.getElementById('output_Max_Temp').innerHTML = '';
  document.getElementById('output_Min_Temp').innerHTML = '';
  document.getElementById('output_Current_Temp').innerHTML = '';
}

function getWeather() {
  var city = document.getElementsByName('city_name')[0].value,
    invalidCity = document.getElementById('invalid_City'),
    stateSelect = document.getElementById('state_select'),
    state = stateSelect.options[stateSelect.selectedIndex].value,
    weatherData = ajaxWeather(city, state),
    weatherObject = JSON.parse(weatherData);

  if (weatherObject.cod == 200) {
    invalidCity.innerHTML = '';
    displayWeather(weatherObject);
  } else {
    invalidCity.innerHTML = 'Please Enter a Valid City!';
    clearOutputData();
  }
}

function applySettings(settings) {
  document.getElementById('state_select').value = settings.state;
  document.getElementsByName('city_name')[0].value = settings.city;
  document.getElementsByName('Wind')[0].checked = settings.getWind;
  document.getElementsByName('Max_Temp')[0].checked = settings.getMaxT;
  document.getElementsByName('Min_Temp')[0].checked = settings.getMinT;
  document.getElementsByName('Current_Temp')[0].checked = settings.getCurrentT;
}

window.onload = function() {
  var settings = localStorage.getItem('weatherSettings');
  if (settings === null) {
    settings = new Weather('', 'OR', true, true, true, true);
    localStorage.setItem('weatherSettings', JSON.stringify(settings));
  } else {
    settings = JSON.parse(localStorage.getItem('weatherSettings'));
  }
  applySettings(settings);
};