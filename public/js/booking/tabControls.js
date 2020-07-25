var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");

  x[n].style.display = "block";
  x[n+1].style.display = "none";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";

    // document.getElementById("nextBtn").innerHTML = "Next";
  } else {
    document.getElementById("prevBtn").style.display = "inline";

  }
  // if (n == (x.length - 1)) {
  //   document.getElementById("nextBtn").innerHTML = "Submit";
  // } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  // }
  // ... and run a function that displays the correct step indicator:
  // fixStepIndicator(n)
}

function nextPrev(n) {

  var x = document.getElementsByClassName("tab");

  x[currentTab].style.display = "none";

  currentTab = currentTab + n;

  showTab(currentTab);
}
