// getSuggestions(str) - sends search terms 'str' to
//     citySuggestions.php, which returns the top 10 matching cities
//     (by # of contributors)
function getSuggestions(str) {
  if (str.length == 0) {
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
       fillSuggestions(this.responseText);
      }
    };
    xmlhttp.open("GET", "citySuggestions.php?q=" + str, true);
    xmlhttp.send();
  }
}


// fillSuggestions() - adds top search results obtained from
//   getSuggestions() to datalist
function fillSuggestions(str) {
  clearSuggestions();
  str = str.split(';;');
  for(let i = 0; i < str.length - 1; i++) {
    var opt = document.createElement('option');
    var cityData = str[i].split('::');
    opt.value = cityData[0];
    opt.innerHTML = cityData[1];
    document.getElementById('searchSuggestions').appendChild(opt)
  }
}


// clearSuggestions() - removes all suggestions from datalist
function clearSuggestions() {
  document.getElementById('searchSuggestions').innerHTML = '';
}
