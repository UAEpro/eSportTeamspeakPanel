var xmlhttp_players = new XMLHttpRequest();
var data = [];
xmlhttp_players.onreadystatechange = function() {
    if (xmlhttp_players.readyState == 4 && xmlhttp_players.status == 200) {
        var myArr = JSON.parse(xmlhttp_players.responseText);
		if(myArr.status == "success"){
        for(var key in myArr.result.data)
        {
	      data[data.length] = {time:key, players:myArr.result.data[key]};
        }
		}

    Morris.Line({
        element: 'morris-area-chart',
		behaveLikeLine: true,
        data: data,
        xkey: 'time',
        ykeys: ['players'],
		ymin: 'auto',
        labels: ['عدد المتصلين'],
        pointSize: 0,
        hideHover: 'auto',
        resize: true,
		lineColors: ['#880000']
    });
  }
}
xmlhttp_players.open("GET", 'https://api.planetteamspeak.com/serverhistory/ts.esport.ae/?duration=2', true);
xmlhttp_players.send();

var xmlhttp_youtubers = new XMLHttpRequest();
var data_youtube = [];
xmlhttp_youtubers.onreadystatechange = function() {
    if (xmlhttp_youtubers.readyState == 4 && xmlhttp_youtubers.status == 200) {
        var myArr = JSON.parse(xmlhttp_youtubers.responseText);
		for(var key in myArr)
        {
			data_youtube[data_youtube.length] = {y:myArr[key][0], a:myArr[key][1]};
		}
		    Morris.Bar({
        element: 'morris-bar-chart',
        data: data_youtube,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['عدد المشتركين'],
        hideHover: 'true',
        resize: true,
		 barColors: function (row, series, type) {
    if (type === 'bar') {
      var red = Math.ceil(255 * row.y / this.ymax);
      return 'rgb(' + red + ',0,0)';
    }
    else {
      return '#000';
    }
  }

    });
  }
}
xmlhttp_youtubers.open("GET", 'http://esport.ae/ePanel/Youtube/Top.php', true);
xmlhttp_youtubers.send();

var xmlhttp_games = new XMLHttpRequest();
var data_games = [];
xmlhttp_games.onreadystatechange = function() {
    if (xmlhttp_games.readyState == 4 && xmlhttp_games.status == 200) {
        var myArr = JSON.parse(xmlhttp_games.responseText);
		for(var key in myArr)
        {
			data_games[data_games.length] = {label:myArr[key][0], value:myArr[key][1]};
		}
    Morris.Donut({
        element: 'morris-donut-chart',
        data: data_games,
		  colors: [
			'#FFA462',
			'#DDB580',
			'#AAC69D',
			'#88D7BB'
		  ],
        resize: true
    });
  }
}
xmlhttp_games.open("GET", 'http://esport.ae/ePanel/Top5Games.php', true);
xmlhttp_games.send();