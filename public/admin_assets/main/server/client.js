var ws=new  WebSocket('');

ws.onopen=function(){
console.log('connected');
}

ws.onmessage=function(){
	$.ajax({
			url: baseUrl+'/getdashboarddatas',
			type: "get",
			success: function(responce){
				var val = JSON.parse(responce);
				var total_accounts = val['total_accounts'];
				var female_per = val['female_per'];
				var random_chats = val['random_chats'];
				var random_per = val['today_stream_per'];
				var subscribers = val['subscribers'];
				var total_transcations = val['total_transcations'];
				var membership_transcations = val['membership_transcations'];
				var gems_transcations = val['gems_transcations'];
				var total_countries = val['total_countries'];
				var mostused_country = val['mostused_country'];
				var mostused_per = val['mostused_per'];
				var membership_transt_per = val['membership_transt_per'];
				var gems_transt_per = val['gems_transt_per'];
				var today_transt_per = val['today_transt_per'];
				var sub_per = val['sub_per'];
				var user_report = val['user_report'];
				var user_report_per = val['user_report_per'];
				$('#total_accounts').text(total_accounts);
				$('#female_per').text(female_per);
				$('#random_chats').text(random_chats);
				$('#random_per').text(random_per);
				$('#subscribers').text(subscribers);
				$('#total_transcations').text(total_transcations);
				$('#membership_transcations').text(membership_transcations);
				$('#gems_transcations').text(gems_transcations);

				$('#total_countries').text(total_countries);
				$('#mostused_country').text(mostused_country);
				$('#mostused_per').text(mostused_per);
				$('#membership_transt_per').text(membership_transt_per);
				$('#gems_transt_per').text(gems_transt_per);
				$('#today_transt_per').text(today_transt_per);
				$('#sub_per').text(sub_per);
				$('#user_report').text(user_report);
				$('#user_report_per').text(user_report_per);
			}
		});
}
