const config = require("../config/keys");
const moment = require("moment");
const lib = require("../libraries/abbreviateNumber");
let dbModel = require("../models/trendingModel");
let streamModel = require("../models/streamModel");
let hashtagModel = require("../models/hashtagModel");

exports._updateHastagStatistics = function(trendingHashtags) {
	Object.keys(trendingHashtags).forEach(function(key) {
		if (trendingHashtags[key].hasOwnProperty("_id") && trendingHashtags[key].hasOwnProperty("total")){
			let SearchQuery = [
			{"$match" : {"hashtags":trendingHashtags[key]["_id"], "mode" : "public"}},
			{"$project": {"_id":0,"publisher_id":1,"title":1,"viewers":1,"likes": 1,"mode":1,"type":"$live_status", "stream_name":"$name" ,"stream_thumbnail":{ $concat: [ config.IMAGE_BASE_URL, "streams/", "$thumbnail" ] }  }},  
			{
				"$lookup" : { from: "accounts", localField: "publisher_id", foreignField: "_id", as: "userData" }
			},
			{ "$sort": { "type": 1}},
			{ "$limit": 10 } 
			];	

			let searchString = {};
			searchString.active_status = 0;
			searchString.mode = "public";
			searchString.hashtags = trendingHashtags[key]["_id"];

			let streamsQuery = streamModel.find(searchString)
			.populate("publisher_id")
			.sort({"live_status": 1, "viewers":-1,"likes" : -1})
			.skip(0)
			.limit(10);

			//streamModel.aggregate(SearchQuery).exec(function(err, HastagStreams) {
			streamsQuery.exec(function(err, HastagStreams) {
				if(!err && HastagStreams){
					let HastagStreamData = [];
					HastagStreams.forEach(function(eachHashtagStream){
						HastagStreamData.push({ "title":eachHashtagStream.title ,"posted_by":eachHashtagStream.publisher_id.acct_name ,"stream_name":eachHashtagStream.name , "stream_thumbnail":config.IMAGE_BASE_URL + "streams/" + eachHashtagStream.thumbnail,"type": eachHashtagStream.live_status,"publisher_image": config.IMAGE_BASE_URL + "accounts/" + eachHashtagStream.publisher_id.acct_photo})
					});

					/*console.log(JSON.stringify(HastagStreamData));*/

					var query = { "name": trendingHashtags[key]["_id"]},
					update = { "name": trendingHashtags[key]["_id"],total: lib.abbreviateNumber(trendingHashtags[key]["total"]),points:trendingHashtags[key]["total"],streams:HastagStreamData,updated_at: moment().toISOString() },
					options = { upsert: true, new: true, setDefaultsOnInsert: true  };
					dbModel.findOneAndUpdate(query, update, options, function(error, result) {
						if (error) {
							/*console.log(error);*/
						}
						else
						{
							/*console.log("trending statstics updated");*/
						}
					});

				}
			});
		}
	});
};

exports._incrTrendingHashtagTotal = function(eachHashtag,streamsCount) {
	let hashtagQuery = dbModel.findOneAndUpdate(
	{
		name: eachHashtag,
	},
	{
		points: streamsCount,
		total: lib.abbreviateNumber(streamsCount)
	}
	);
	hashtagQuery.exec(function(err, streamInfo) {
		if (err) {
			/*console.log("Error is" + streamInfo );*/
		}
	});
};


exports.trendingHashtags = function(req, res) {
	let SearchQuery = dbModel.find({points: { $gt: 0 }}).sort({ points: -1 }).limit(10);
	SearchQuery.exec(function(err, hashtag_details) {
		if (!err && hashtag_details) {
			return res.json({
				status: "true",
				result: hashtag_details
			});
		}
		else
		{
			return res.json({ status: "false", message: "No data found :)" });
		}
	});
}