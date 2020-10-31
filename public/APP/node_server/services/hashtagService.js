const config = require("../config/keys");
const cron = require("node-cron");
const lib = require("../libraries/abbreviateNumber");

let dbModel = require("../models/hashtagModel");
let streamModel = require("../models/streamModel");

let streamService = require("../services/streamService");
let trendingService = require("../services/trendingService");

exports.listAllHashtags = function(req, res) {
	if (!req.body.user_id) {
		return res.json({
			status: "false",
			message: "Something went to be wrong"
		});
	} else {
		let offset = 0;
		let limit = 20;
		let filterSearch = { };
		
		if (req.body.limit) {
			limit = parseInt(req.body.limit);
		}

		if (req.body.offset) {
			offset = parseInt(req.body.offset);
		}

		if (req.body.search_key) {
			filterSearch.topic = { $regex: req.body.search_key, $options: "i" };
		}

		let SearchQuery = dbModel.find(filterSearch).sort({ created_at: -1 })
		.skip(offset)
		.limit(limit);

		SearchQuery.exec(function(err, hashtag_details) {
			if (!err && hashtag_details) {
				let allhashtags = [];
				hashtag_details.forEach(function(hashTagInfo) {
					allhashtags.push({
						name: hashTagInfo.topic,
						count: lib.abbreviateNumber(hashTagInfo.total),
					});
				});
				if(allhashtags.length > 0)
				{
					return res.json({
						status: "true",
						result: allhashtags
					});
				}
				else
				{
					return res.json({
						status: "false",
						message: "No hashtags found"
					});
				}
			} else {
				return res.json({
					status: "false",
					message: "Something went to be wrong"
				});
			}
		});
	}
};

exports.exploreStreams = function(req, res) {
	if (!req.body.user_id || !req.body.type || !req.body.search_key) {
		return res.json(errMsg);
	} else {
		let offset = 0;
		let limit = 20;
		let searchString = {};

		searchString.active_status = 0;
		searchString.mode = "public";

		if (req.body.limit) {
			limit = parseInt(req.body.limit);
		}

		if (req.body.offset) {
			offset = parseInt(req.body.offset);
		}

		if(req.body.type == "country"){
			searchString.publisher_location = req.body.search_key;
		}

		if(req.body.type === "hashtag"){
			searchString.hashtags = req.body.search_key;  
		}


		let matchStreams = streamModel.countDocuments(searchString);

		let streamsQuery = streamModel.find(searchString)
		.populate("publisher_id")
		.sort({"live_status": 1, "viewers":-1,"likes" : -1})
		.skip(offset)
		.limit(limit);

		matchStreams.exec(function(err, matchStreamsCount) {
			streamsQuery.exec(function(err, allstreamInfo) {
				if (!err && allstreamInfo.length > 0) {
					let streamResult = [];
					allstreamInfo.forEach(function(streamInfo) {
						streamResult.push({
							name: streamInfo.name,
							mode: streamInfo.mode,
							title: streamInfo.title,
							watch_count: streamInfo.viewers,
							streamed_on: streamInfo.created_at,
							stream_id: streamInfo._id,
							publisher_id: streamInfo.publisher_id._id,
							publisher_image: config.IMAGE_BASE_URL + "accounts/" + streamInfo.publisher_id.acct_photo,
							stream_thumbnail: config.IMAGE_BASE_URL + "streams/" + streamInfo.thumbnail,
							posted_by: streamInfo.publisher_id.acct_name,
							type: streamInfo.live_status,
							likes: streamInfo.likes,
							playback_duration: streamInfo.playback_duration,
							playback_ready: streamInfo.playback_ready,
							playback_url: streamInfo.playback_url
						});
					});

					return res.json({ status: "true", result: { name: req.body.search_key, total: matchStreamsCount.toString(), streams: streamResult } });

				} else {
					return res.json({ status: "false", message: "No data found :)" });
				}
			});

		});
	}
};

cron.schedule("* * * * *", () => {
	const trendingHashtagsQuery = [
	{ "$match": {"mode" : "public" } },
	{ "$project": { "hashtags":1}},  
	{ "$unwind": "$hashtags" },  
	{ "$group": { "_id": "$hashtags", "total": { "$sum": 1 }}},
	{ "$sort": { "total": -1 }},
	{ "$limit": 10 } ,
	];	

	streamModel.aggregate(trendingHashtagsQuery).exec(function(err, trendingHashtags) {
		trendingService._updateHastagStatistics(trendingHashtags);
	});

	/*const trendingHashtagsQuery = [
	{ "$project": { "_id": "$topic","total":"$total"}},
	{ "$sort": { "total": -1 }},
	{ "$limit": 10 } ,
	];	
	dbModel.aggregate(trendingHashtagsQuery).exec(function(err, trendingHashtags) {
		trendingService._updateHastagStatistics(trendingHashtags);
	});*/
});

exports._incrHashtagTotal = function(eachHashtag) {
	let streamsQuery = streamModel.countDocuments({"hashtags": eachHashtag, mode: "public"});
	streamsQuery.exec(function(err, streamsCount) {
		if (!err) {
			let hashtagQuery = dbModel.findOneAndUpdate(
			{
				topic: eachHashtag,
			},
			{
				total: streamsCount,
			}
			);
			hashtagQuery.exec(function(err, streamInfo) {
				if (!err) {	
					/*console.log("Updated successfully");*/
				}
				trendingService._incrTrendingHashtagTotal(eachHashtag,streamsCount);
			});
		}
	});
};



