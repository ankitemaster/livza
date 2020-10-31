
function setlang() {
    var lang = $("#language").val();
    window.location = baseUrl + "/lang/" + lang;
}

$("#search").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);
});

$("#val-invitegems").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});


  function num(value)
  {
    if (/\D/g.test(value)) value = value.replace(/\D/g,'')
  }

$("#val-red-sub").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});

$("#val-red-unsub").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});

$("#val-gems").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});

$(".numberonly").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});

$(".priceonly").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return floatpriceValid(character);
});

$("#val-gems").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return intpriceValid(character);
});

$(".search_filter").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);
});

function isValid(str) {
    return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
}

function floatpriceValid(str) {
    var newVal = str;
    var regexp = /^(?:[\d-]*,?[\d-]*\.?[\d-]*|[\d-]*\.[\d-]*,[\d-]*)$/;

    if (regexp.test(newVal)) {
        return true;
    } else {
        return false;
    }
}

function intpriceValid(str) {
    var newVal = str;
    var regexp = /^(0|[1-9]+[0-9]*)$/;

    if (regexp.test(newVal)) {
        return true;
    } else {
        return false;
    }
}


function alphanumeric(str) {
    var newVal = str;
    var regexp =  /^[a-zA-Z 0-9\.\,\+]*$/;

    if (regexp.test(newVal)) {
        return true;
    } else {
        return false;
    }
}

$("#val-reporttitles").keypress(function(event) {
   
    var character = String.fromCharCode(event.keyCode);
    return alphanumeric(character);
});

function notifyalert(id, type) {
    if (type == 1) {
        $.ajax({
            url: baseUrl + "/accounts/sendalert/" + id + "/1",
            type: "get",
            data: { type: type },
            beforeSend: function() {
                $("#btn" + id).attr("style", "pointer-events: none");
            },
            success: function(responce) {
                $("#rec" + id).remove();
            }
        });
    }
}




$(function(){

  //get the pie chart canvas
  var ctx1 = $("#myChart3");
  var ctx2 = $("#myChart4");
  var ctx3 = $("#myChart5");
  

  //doughnot chart data
  var data1 = {
    labels: ["Male", "Female","Male", "Female", "Female"],
    datasets: [
      {
        label: "Gender Ratio",
        data: [10, 50,90,120,67],
        backgroundColor: [
            "#5E1084",
          "#1dc9b7",
          "#5578eb",
          "#ffb822",
          "#fd397a"
         
         
        ],
        borderColor: [
            "#5E1084",
            "#1dc9b7",
            "#5578eb",
            "#ffb822",
            "#fd397a"
           
        ],
        borderWidth: [1, 1,1,1,1]
      }
    ]
  };


  //pie chart data
  var data2 = {
    labels: ["UK", "Italy","Indonesia","Jamaica","Ukraine"],
    datasets: [
      {
        label: "People Ratio",
        data: [10, 50,90,120,67],
        backgroundColor: [
          "#5E1084",
          "#1dc9b7",
          "#5578eb",
          "#ffb822",
          "#fd397a"
         
        ],
        borderColor: [
            "#5E1084",
            "#1dc9b7",
            "#5578eb",
            "#ffb822",
            "#fd397a"
        ],
        borderWidth: [1, 1,1, 1,1]
      }
    ]
  };

  //options
  var options = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Gender Ratio",
      fontSize: 18,
    },
    legend: {
      display: false,
      position: "bottom",
      labels: {
        fontColor: "#333",
        fontSize: 16
      }
    }
  };

  //options
  var options2 = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Gender Ratio",
      fontSize: 18,
      fontColor: "#111"
    },
    legend: {
      display: true,
      position: "bottom",
      labels: {
        fontColor: "#333",
        fontSize: 16
      }
    }
  };

  //create Chart class object
  var chart1 = new Chart(ctx1, {
    type: "doughnut",
    data: data1,
    options: options
  });

  //create Chart class object
  var chart2 = new Chart(ctx2, {
    type: "pie",
    data: data2,
    options: options2
  });

  var chart3 = new Chart(ctx3, {
    type: 'bar',
    data: data2,
    options: options
 });

});


