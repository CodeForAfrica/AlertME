/**
 * Pahali.JS MODELS
 * -----------------------------------------------------------------------------
 */

pahali.datasources = {};

pahali.datasource = {};

pahali.datasource.config = {
  id: {
    title: 'ID', col: -1
  },
  title: {
    title: 'Title', col: -1
  },
  desc: {
    title: 'Description', col: -1
  },
  geo: {
    title: 'Geo Type',
    type: 'lat_lng',
    lat_lng: {
      lat: {
        title: 'Geo Lat', col: -1
      },
      lng: {
        title: 'Geo Lng', col: -1
      }
    },
    address: {
      title: 'Geo Address', col: -1
    }
  },
  status: {
    title: 'Status', col: -1
  }
};


pahali.project = {};
pahali.projects = {};

pahali.subscribe = {};
