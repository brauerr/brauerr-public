//Your library code goes in this file.

function ajaxRequest(URL, Type, Parameters) {
  //URL is base URL the request will be made to
  //type will be string 'POST' or string 'GET'
  //if POST send as 'application/x-www-form-urlencoded' (set content type in the header)
  //if GET then create proper URL string and use within function
  //parameters will be obejct containing key value pairs of strings {'name': 'John', 'thing':'foo'};
  
  var myCode,
    myCodeDetail,
    myResponse;
    
    
  
  //success true if response had a code corresponding with success
  //code holds response code
  //codeDetail holds text associated with the particular status code
  //response should be string representation of the response received from the server  
    
  return {'success': true, 'code': myCode, 'codeDetail': myCodeDetail, 'response': myResponse};
}

function localStorageExists() {
  //should return true if able to read and write from local storage
  //else return false
  var testPair = {'someData': 'foo'};
  localStorage.setItem('mySettings', testPair);
  var settingsTest = localStorage.getItem('mySettings');
  if(settingsTest !== undefined) {
    return true;
  }
  return false;
}