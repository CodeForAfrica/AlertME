/**
 * Pahali.JS Data Sources CONTROLLER
 * -----------------------------------------------------------------------------
 */


pahali.datasources.update = function (id, callback) {
  $.ajax({
    type: "PUT",
    async: false,
    url: pahali.base_url + "/api/v1/datasources/" + pahali.datasources[id].id,
    data: pahali.datasources[id]
  }).done(function( response ) {
    pahali.datasources[id] = response.datasource;
    if(typeof callback === "function")  {
      callback(id);
    }
  });
};


pahali.datasources.pull = function (callback) {
  $.ajax({
    type: "GET",
    async: false,
    url: pahali.base_url + "/api/v1/datasources"
  }).done(function( response ) {
    $.extend(pahali.datasources, response.datasources);
    if(typeof callback === "function") {
      callback();
    }
  });
};
