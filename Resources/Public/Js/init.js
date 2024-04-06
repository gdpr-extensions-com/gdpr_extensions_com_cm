const cookiesRejected = localStorage.getItem('cookiesRejected');
if (!cookiesRejected || cookiesRejected === 'true') {

  window.YETT_BLACKLIST = [
    /.*/
  ];

}
