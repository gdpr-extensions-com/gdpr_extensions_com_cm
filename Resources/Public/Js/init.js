const coookieValue = localStorage.getItem('cookiesRejected');
const cookiesRejected = coookieValue === "true";

if (cookiesRejected) {
  console.log("This is true");
} else {
  console.log("This is false");
}
if (cookiesRejected) {
  window.YETT_BLACKLIST = [
    /.*/
  ];
}
else {
  window.YETT_BLACKLIST = [];
}