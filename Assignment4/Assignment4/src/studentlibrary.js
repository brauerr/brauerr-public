//Your library code goes in this file.

function ajaxRequest(URL, Type, Parameters) {
  //URL is base URL the request will be made to
  //type will be string 'POST' or string 'GET'
  //if POST send as 'application/x-www-form-urlencoded' (set content type in the header)
  //if GET then create proper URL string and use within function
  //parameters will be obejct containing key value pairs of strings {'name': 'John', 'thing':'foo'};
  
  var success,
    myCode,
    myCodeDetail,
    myResponse
    getURL,
    postPayload,
    
  var myRequest = new XMLHttpRequest(); //for IE may not support earlier than 10
  if(!myRequest) {
    throw 'Unable to create HttpRequest.';
  }
  
  if(Type == 'GET') {
    getURL = URL + '?' + EncodeQueryData(Parameters);
    myRequest.onreadystatechange = processMyRequest();
    myRequest.open('GET', getURL);
    myRequest.send();
  }
  else if(Type == 'POST') {
    myRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    myRequest.onreadystatechange = processMyRequest();
    myRequest.open('POST', URL);
    //set up as header payload (same format as text after '?' in GET)
    myRequest.send(EncodeQueryData(Parameters));
  }
  else {
    throw 'Type String not GET or POST';
  }  
  
  function processMyRequest() {
    if(this.readyState === 4) {
      if(this.status === 200) {
        success = true;
      }
      else {
        success = false;
      }
      myCode = this.status;
      myCodeDetail = this.statusText;
      myResponse = this.responseText;
    }
  }
  
  //success true if response had a code corresponding with success
  //code holds html response code
  //codeDetail holds text associated with the particular html status code
  //response should be string representation of the response data received from the server  
    
  return {'success': true, 'code': myCode, 'codeDetail': myCodeDetail, 'response': myResponse};
}

//Helper function to take key pairs of strings and put in format to construct GET request
//Source:  http://stackoverflow.com/questions/111529/create-query-parameters-in-javascript
function EncodeQueryData(data)
{
  var ret = [];
  for(var d in data) {
    ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]);
  }
  return ret.join('&');
}

function localStorageExists() {
  //should return true if able to read and write from local storage
  //else return false
  localStorage.clear();
  var testString= 'testing123';
  localStorage.setItem('testLocalStorage', testString);
  var settingsTest = localStorage.getItem('testLocalStorage'); //set to null if getItem() fails
  if(settingsTest !== null) {
    return true;
  }
  return false;
}