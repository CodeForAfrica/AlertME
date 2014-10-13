$( document ).ready(function() {




  var parameter = getUrlParameters("map", "", true);

  console.log(parameter);

});

function getUrlParameters(parameter, staticURL, decode){
  /*
  Function: getUrlParameters
  Description: Get the value of URL parameters either from
               current URL or static URL
  Author: Tirumal
  URL: www.code-tricks.com
  */
  var currLocation = (staticURL.length)? staticURL : window.location.hash;
  if (!currLocation) return false ;
  var parArr = currLocation.split("#!/")[1].split("&");
  var returnBool = true;


  for(var i = 0; i < parArr.length; i++){
    parr = parArr[i].split("=");
    if(parr[0] == parameter){
      return (decode) ? decodeURIComponent(parr[1]) : parr[1];
      returnBool = true;
    }else{
      returnBool = false;
    }
  }

  if(!returnBool) return false;
}
