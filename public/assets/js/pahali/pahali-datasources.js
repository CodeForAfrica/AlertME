/**
 * Pahali.JS Data Sources CONTROLLER
 * -----------------------------------------------------------------------------
 */

pahali.datasources.update = function (id) {
  $.ajax({
    type: "PUT",
    async: false,
    url: pahali.base_url + "/api/v1/datasources/" + pahali.datasources[id].id,
    data: pahali.datasources[id]
  }).done(function( response ) {
    pahali.datasources[id] = response.datasource;
  });
  return pahali.datasources[id];
};
