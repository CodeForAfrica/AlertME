/**
 * Pahali.JS
 * -----------------------------------------------------------------------------
 *
 * The Pahali.JS extension.
 *
 */

var pahali = {
  _name: 'Pahali.JS',
  _description: 'The Pahali.JS extension',
  base_url: '',
  csrf_token: '',
  country: {
    code: 'za'
  }
};


document.write('<script src="/assets/js/pahali/pahali-models.js"><\/script>');

document.write('<script src="/assets/js/pahali/pahali-datasources.js"><\/script>');
document.write('<script src="/assets/js/pahali/pahali-map.js"><\/script>');
document.write('<script src="/assets/js/pahali/pahali-categories.js"><\/script>');

document.write('<script src="/assets/js/pahali/pahali-projects.js"><\/script>');

document.write('<script src="/assets/js/pahali/pahali-subscribe.js"><\/script>');
