const path = require("path");
const async = require("async");
const cron = require("node-cron");
const multer = require("multer");
const request = require("request");
const crypto = require("crypto");
const urllib = require("urllib");
const moment = require("moment");
const randomGenerator = require("randomstring");
const config = require("../config/keys");
const DbModel = require("../models/streamModel");
const ReportsModel = require("../models/streamreportModel");
const AccountsModel = require("../models/accountModel");
const FollowingsModel = require("../models/followingModel");
const BlocksModel = require("../models/blockModel");
const WatchHistoryModel = require("../models/watchhistoryModel");
const lib = require("../libraries/abbreviateNumber");
const { getVideoDurationInSeconds } = require('get-video-duration');
const urlExists = require('url-exists');
const sightengine = require("sightengine")(
    config.SIGHTENGINE._userid,
    config.SIGHTENGINE._secretkey
    );
let mongoose = require("mongoose");
let errMsg = {
    status: "false",
    message: "Something went to be wrong"
};

/* services */
let redisServer = require("../services/redisService");
let accountService = require("../services/accountService");
let watchhistoryService = require("../services/watchhistoryService");
let hashtagService = require("../services/hashtagService");
let notificationService = require("./notificationService");

exports.createStream = function(req, res) {
    if (req.params.publisher_id) {
        let accountsQuery = AccountsModel.countDocuments({
            _id: req.params.publisher_id,
            acct_status: 1
        }).limit(1);
        accountsQuery.exec(function(err, accountexists) {
            if (!err && accountexists > 0) {
                let randomName = randomGenerator.generate(12);
                let streamName = "Stream_" + randomName; 
                return res.json({
                    status: "true",
                    stream_name: streamName,
                    stream_token: _hashAuthToken(streamName,"publish"),
                    invite_link: "appstore?stream_name=" + streamName, 
                });
            } else {
                return res.json({
                    status: "accountblocked",
                    message: "Your account will be active in 24 hrs. For details Kindly contact admin."
                });
            }
        });
    } else {
        return res.json(errMsg);
    }
};

exports.startStream = function(req, res) {
    if (!req.body.publisher_id || !req.body.title || !req.body.name || !req.body.recording || !req.body.mode) {
        return res.json(errMsg);
    } else {
        AccountsModel.countDocuments(
        {
            _id: req.body.publisher_id
        },
        function(err, count) {
            if (count > 0 && !err) {
                AccountsModel.findOne(
                {
                    _id: req.body.publisher_id
                },
                function(err, accountInfo) {
                    if (accountInfo && !err) {
                        req.body.publisher_age = accountInfo.acct_age;
                        req.body.publisher_gender = accountInfo.acct_gender;
                        req.body.publisher_location = accountInfo.acct_location;
                        if(req.body.hashtags){
                            req.body.hashtags = req.body.hashtags.split(",");
                            req.body.hashtags = req.body.hashtags.filter(function (e) {return ( e != null && e != "");});
                        }

                        if(req.body.group_members){
                            req.body.group_members = req.body.group_members.split(",");
                            req.body.group_members = req.body.group_members.filter(function (e) {return ( e != null && e != "");});
                            notificationService.sendNotifications(req.body.group_members,{scope:"streaminvitation",stream_name:req.body.name,message:"You're invited to watch the stream"});
                        }

                        req.body.playback_url = config.ANTMEDIA.vod_url + req.body.name + ".mp4";
                        let newStreams = new DbModel(req.body);
                        newStreams.save(function(err, streamdata) {
                            if (!err) {

                                accountService.profileStreams(req.body.publisher_id);

                                if(req.body.hashtags)
                                {

                                    for (let i = 0; i < req.body.hashtags.length; i++) {
                                        hashtagService._incrHashtagTotal(req.body.hashtags[i]);
                                    }
                                }
                                
                                if(req.body.mode === "public"){
                                    _notifyMyFollowers(req.body.publisher_id,accountInfo.acct_name,req.body.name);
                                }

                                return res.json({
                                    status: "true",
                                    message: "Streaming is live now"
                                });
                            } else {
                                return res.json(errMsg);
                            }
                        });
                    } else {
                        return res.json(errMsg);
                    }
                }
                );
            } else {
                return res.json(errMsg);
            }
        }
        );
    }
};

exports.stopStream = function(req, res) {
    if (!req.body.publisher_id || !req.body.name) {
        return res.json(errMsg);
    } else {
        let streamsQuery = DbModel.findOneAndUpdate(
        {
            publisher_id: req.body.publisher_id,
            name: req.body.name
        },
        {
            playback_duration: req.body.duration,
            live_status: "recorded",
            updated_at: moment().toISOString()
        }
        );
        streamsQuery.exec(function(err, streamInfo) {
            if (!err) {
                if(streamInfo){
                    
                    if(streamInfo.name && streamInfo.publisher_id) { 
                        broadcastEnds(streamInfo.name, streamInfo.publisher_id, "recorded");
                    }
                    
                    return res.json({
                        status: "true",
                        message: "Streaming is ended now"
                    });
                }
                else
                {
                    return res.json(errMsg); 
                }
            } else {
                return res.json(errMsg);
            }
        });
    }
};

exports.streamDetails = function(req, res) {
    if (!req.body.user_id || !req.body.name) {
        return res.json(errMsg);
    } else {
        
        let streamExistsQuery = DbModel.countDocuments({
            name: req.body.name
        }).populate("publisher_id");

        streamExistsQuery.exec(function(err, streamExists) {
            if(!err && streamExists > 0) {
                let user_id = req.body.user_id;
                let tasks = [
                function generalDetails(cb) {
                    let streamsQuery = DbModel.findOne({
                        name: req.body.name
                    }).populate("publisher_id");
                    streamsQuery.exec(function(err, streamInfo) {
                        if (!err) {
                            let result = {};
                            result.status = "true";
                            result.stream_id = streamInfo._id;
                            result.mode = streamInfo.mode;
                            result.group_members = streamInfo.group_members;
                            result.hashtags = streamInfo.hashtags;
                            result.type = streamInfo.live_status;
                            result.name = streamInfo.name;
                            result.stream_blocked = streamInfo.active_status;
                            result.title = streamInfo.title;
                            result.posted_by = streamInfo.publisher_id.acct_name;
                            result.publisher_id = streamInfo.publisher_id._id;
                            result.likes = streamInfo.likes.toString();
                            result.stream_thumbnail = config.IMAGE_BASE_URL + "streams/" + streamInfo.thumbnail;
                            result.publisher_image = config.IMAGE_BASE_URL + "accounts/" + streamInfo.publisher_id.acct_photo;
                            result.playback_url = streamInfo.playback_url,
                            result.watch_count = streamInfo.viewers.toString();
                            result.invite_link = streamInfo.invite_link;
                            let com_dataresult = redisServer.getRedis("live_streams_comment", streamInfo.name);
                            com_dataresult.then(comment => {
                                result.comments = [];
                                /*console.log(Object.keys(comment).length);*/
                                if (Object.keys(comment).length != "0") {
                                    let commentList = JSON.parse(comment);
                                    result.comments = commentList;
                                }
                                return cb(null, result);
                            });
                        } else {
                            return cb(err);
                        }
                    });
                },
                function followStatus(result, cb) {
                    FollowingsModel.countDocuments(
                    {
                        user_id: result.publisher_id,
                        follower_id: user_id
                    },
                    function(err, followingCount) {
                        if (!err) {
                            let followed = followingCount > 0 ? "true" : "false";
                            result.follow = followed;
                            return cb(null, result);
                        } else {
                            return cb(err);
                        }
                    }
                    );
                },
                function reportStatus(result, cb) {
                    ReportsModel.countDocuments(
                    {
                        stream_id: result.stream_id,
                        user_id: user_id
                    },
                    function(err, reportedCount) {
                        if (!err) {
                            let reported = reportedCount > 0 ? "true" : "false";
                            result.reported = reported;
                            return cb(null, result);
                        } else {
                            return cb(err);
                        }
                    }
                    );
                },
                function streamGifts(result, cb) {
                    let com_dataresult = redisServer.getRedis("live_streams_gifts", result.name);
                    com_dataresult.then(gifts => {
                        result.live_gifts = [];
                        if (Object.keys(gifts).length != "0") {
                            let giftList = JSON.parse(gifts);
                            result.live_gifts = giftList;
                        }
                        return cb(null, result);
                    });
                }
                ];
                async.waterfall(tasks, (err, results) => {
                    if (!err) {
                        return res.json(results);
                    } else {
                        return res.json({
                            status: "false",
                            message: "Something went to be wrong"
                        });
                    }
                });
            }
            else
            {
                return res.json({
                    status: "false",
                    message: "No Stream found"
                });
            }
        });
}
};

let streamAssets = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, path.join(__dirname, config.ASSETS + "/streams/"));
    },
    filename: (req, file, cb) => {
        cb(null, file.fieldname + "-" + Date.now());
    }
});

exports.uploadStream = function(req, res) {
    let stream_storage = multer({
        storage: streamAssets
    }).single("stream_image");
    stream_storage(req, res, function(err) {
        if (!err) {
            let searchString = {
                name: req.body.name,
                publisher_id: req.body.publisher_id
            };
            if (config.SIGHTENGINE._mode == "off") {
                DbModel.findOne(searchString, function(err, streamInfo) {
                    if (!err && streamInfo) {
                        let stream_pic = streamInfo.thumbnail;
                        let image_path = path.join(__dirname, config.ASSETS + "/streams/" + stream_pic);
                         // remove the stream's old pic
                         unlinkPic(image_path);
                         DbModel.findOneAndUpdate(
                            searchString,
                            {
                                thumbnail: res.req.file.filename
                            },
                            {},
                            (err, doc) => {
                                if (err) {
                                    return res.json(errMsg);
                                } else {
                                    return res.json({
                                        status: "true",
                                        message: "Image uploaded successfully"
                                    });
                                }
                            }
                            );
                     } else {
                        return res.json(errMsg);
                    }
                });
            } else {
                let pic = "images.jpeg";
                let image_url = config.IMAGE_BASE_URL + "/streams/" + pic;
                let image_path = path.join(
                    __dirname,
                    config.ASSETS + "/streams/" + pic
                    );
                sightengine
                .check(["nudity"])
                .set_url(image_url)
                .then(function(result) {

                    if (result.status == "success") {
                        let moderate_image = result.nudity;
                        if (
                            moderate_image.raw >=
                            Math.max(moderate_image.partial, moderate_image.safe)
                            ) {
                            unlinkFile(image_path); 
                        let streamsQuery = DbModel.findOneAndUpdate(
                        {
                            name: req.body.name,
                            live_status: "recorded"
                        },
                        {
                            active_status: '1',
                            updated_at: moment().toISOString()
                        }
                        );
                        streamsQuery.exec(function(err, streamInfo) {
                            if (err) {
                                /*console.log(err);*/
                            }
                        });
                        return res.json({
                            status: "rejected",
                            message: "Your image was rejected by our moderator."
                        });
                    } else {
                        DbModel.findOne(searchString, function(err, streamInfo) {
                            if (!err && streamInfo) {
                                let stream_pic = streamInfo.thumbnail;
                                let image_path = path.join(__dirname, config.ASSETS + "/streams/" + stream_pic);
                                unlinkPic(image_path); 
                                DbModel.findOneAndUpdate(
                                    searchString,
                                    {
                                        thumbnail: res.req.file.filename
                                    },
                                    {},
                                    (err, doc) => {
                                        if (err) {
                                            return res.json(errMsg);
                                        } else {
                                            return res.json({
                                                status: "true",
                                                message: "Image uploaded successfully"
                                            });
                                        }
                                    }
                                    );
                            } else {
                                return res.json(errMsg);
                            }
                        });
                    }
                } else {
                    return res.json({
                        status: "false",
                        message: "Something went to be wrong"
                    });
                }
            })
                .catch(function(err) {
                    return res.json({
                        status: "false",
                        message: "Something went to be wrong"
                    });
                });
            }

        } else {
            return res.json(errMsg);
        }
    });
};

exports.reportStream = function(req, res) {
    if (!req.body.user_id || !req.body.name) {
        return res.json(errMsg);
    } else {
        let streamsQuery = DbModel.findOne({
            name: req.body.name
        });
        streamsQuery.exec(function(err, streamInfo) {
            if (!err && streamInfo) {
                let searchString = {
                    user_id: req.body.user_id,
                    stream_id: streamInfo._id
                };
                ReportsModel.countDocuments(searchString, function(err, count) {
                    if (count > 0) {
                        ReportsModel.findOneAndRemove(searchString, function(err) {
                            if (!err) {
                                return res.json({
                                    status: "true",
                                    message: "Report Undo successfully"
                                });
                            } else {
                                return res.json(errMsg);
                            }
                        });
                    }
                    else{
                        let reportObject = {
                            user_id: req.body.user_id,
                            stream_id: streamInfo._id,
                            report: req.body.report
                        };
                        let newReport = new ReportsModel(reportObject);
                        newReport.save(function(err) {
                            if (!err) {
                                return res.json({
                                    status: "true",
                                    message: "Reported successfully"
                                });
                            } else {
                                return res.json(errMsg);
                            }
                        });
                    }

                });
            } else {
                return res.json(errMsg);
            }
        });
    }
};

exports.liveStreams = function(req, res) {
    if (!req.body.user_id) {
        return res.json(errMsg);
    } else 
    {
        let searchString = {};
        let sortString;
        let offset = 0;
        let limit = 20;
        let hideStreams = [req.body.user_id];
        let filterStreams = [];

        searchString.active_status = 0;
        searchString.mode = "public";

        if (req.body.limit) {
            limit = parseInt(req.body.limit);
        }

        if (req.body.offset) {
            offset = parseInt(req.body.offset);
        }

        if((req.body.user_id === req.body.profile_id) && (req.body.type === "user"))
        {
            searchString.mode = {
                $in: ['public','private']
            };
        }

        if (req.body.filter_applied === "true") {
            if (req.body.gender && req.body.gender !== "both") {
                searchString.publisher_gender = req.body.gender;
            }

            if (req.body.min_age && req.body.max_age) {
                let search_from = parseInt(req.body.min_age);
                let search_to = parseInt(req.body.max_age);
                searchString.publisher_age = {
                    $gte: search_from,
                    $lte: search_to
                };
            }

            if (req.body.location) {
                let locationString = JSON.parse(req.body.location);
                if (locationString.length > 0) {
                    searchString.publisher_location = {
                        $in: locationString
                    };
                } else {
                    let nolocationString = JSON.parse(req.body.location);
                    searchString.publisher_location = {
                        $nin: nolocationString
                    };
                }
            }
        }

        if (req.body.search_key) {
            searchString.title = { $regex: req.body.search_key, $options: "i" };
        }

        if (req.body.type === "user") {
            searchString.publisher_id = req.body.profile_id;
        } 

        if (req.body.type === "live") { 
            searchString.publisher_id = {
                $nin: hideStreams
            };
        }

        if (req.body.type === "follow") 
        {
            searchString.publisher_id = { $in: filterStreams };
        }

        if (req.body.sort_by) {
            sortString = { "live_status": 1, "viewers":-1,"likes" : -1};
            if(req.body.sort_by === "recent"){
                sortString = { created_at: -1 };
            }
        }

     

        if(req.body.type === "live" || req.body.type === "user" )
        {
            let blockQuery = BlocksModel.find({ blocked_user: req.body.user_id });
            blockQuery.exec(function(err, blockedList) {
                if (!err && blockedList) {
                    blockedList.forEach(function(blockedMember) {
                        hideStreams.push(blockedMember.user_id);
                    });
                }
                let streamsQuery = DbModel.find(searchString)
                .populate("publisher_id")
                .sort(sortString)
                .skip(offset)
                .limit(limit);
                streamsQuery.exec(function(err, allstreamInfo) {
                    if (!err && allstreamInfo.length > 0) {
                        let streamResult = [];
                        allstreamInfo.forEach(function(streamInfo) {
                            if(streamInfo.publisher_id){
                             streamResult.push({
                                name: streamInfo.name,
                                title: streamInfo.title,
                                mode: streamInfo.mode,
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
                                playback_url: streamInfo.playback_url,
                                invite_link:streamInfo.invite_link
                            }); 
                         }
                        });
                        return res.json({ status: "true", result: streamResult });
                    } else {
                        return res.json({ status: "false", message: "No data found :)" });
                    }
                });
            });
        }
        else
        {
            let followingQuery = FollowingsModel.find({ follower_id: req.body.user_id });
            followingQuery.exec(function(err, followingList) {
                if (!err && followingList) {
                    followingList.forEach(function(follower) {
                        filterStreams.push(follower.user_id);
                    });
                }
                let streamsQuery = DbModel.find(searchString)
                .populate("publisher_id")
                .sort(sortString)
                .skip(offset)
                .limit(limit);
                streamsQuery.exec(function(err, allstreamInfo) {
                    if (!err && allstreamInfo.length > 0) {
                        let streamResult = [];
                        allstreamInfo.forEach(function(streamInfo) {
                            streamResult.push({
                                name: streamInfo.name,
                                title: streamInfo.title,
                                mode: streamInfo.mode,
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
                        return res.json({ status: "true", result: streamResult });
                    } else {
                        return res.json({ status: "false", message: "No data found :)" });
                    }
                });
            });

        }
    }
};

exports.deleteStream = function(req, res) {
    if (!req.params.publisher_id || !req.params.name) {
        return res.json(errMsg);
    } else {
        let streamsQuery = DbModel.findOne({ name: req.params.name });
        streamsQuery.exec(function(err, streamInfo) {
            if (!err && streamInfo) {            
                DbModel.findOneAndDelete({ name: req.params.name, publisher_id: req.params.publisher_id }, function(err) {
                    if (!err) {
                        if(streamInfo.hashtags.length > 0)
                        {
                            for (let i = 0; i < streamInfo.hashtags.length; i++) {
                                hashtagService._incrHashtagTotal(streamInfo.hashtags[i]);
                            }
                        }  
                        let streamId = streamInfo._id;
                        ReportsModel.findOneAndDelete({ stream_id: streamId }, function(err, reports) {
                            if (!err) {
                                accountService.delStreams(req.params.publisher_id);
                                watchhistoryService._clearWatchHistory(streamId);
                                _deleteVod(streamId);
                                return res.json({
                                    status: "true",
                                    message: "Video Deleted Successfully"
                                });
                            } else {
                                return res.json(errMsg);
                            }
                        });
                    } else {
                        return res.json(errMsg);
                    }
                });
            } else {
                return res.json(errMsg);
            }
        });
    }
};

exports.postLikes = function(data) {
    if (data.stream_name && data.user_id) {
        let streamsQuery = DbModel.findOneAndUpdate(
        {
            name: data.stream_name,
            live_status: "live"
        },
        {
            $inc: { likes: 1 },
            updated_at: moment().toISOString()
        }
        );
        streamsQuery.exec(function(err, streamInfo) {
            if (err) {
                /*console.log(err);*/
            }
        });
    }
};

exports.commentStream = function(data) {
    if (data.stream_name && data.user_id) {
        let streamName = data.stream_name;
        let submatch = -1;
        let result = redisServer.getRedis("live_streams_comment", data.stream_name);
        result.then(comments => {
            if (comments.length > 0) {
                let commentsList = JSON.parse(comments);
                submatch = commentsList.findIndex(sub => sub.user_id === data.user_id);
            }
            redisServer.pushRedis("live_streams_comment", streamName, data);
        });
    }
};


exports.giftStream = function(data) {
    if (data.stream_name && data.user_id) {
        let streamName = data.stream_name;
        let submatch = -1;
        let result = redisServer.getRedis("live_streams_gifts", data.stream_name);
        result.then(gifts => {
            redisServer.pushRedis("live_streams_gifts", streamName, data);
        });
    }
};

exports.subscribeStream = function(data) {
    if (data.stream_name && data.user_id) {

        let newSubscriber = {
            user_id: data.user_id,
            user_name: data.user_name,
            user_image: data.user_image,
            subscribe_on: moment().toISOString()
        };
        let streamName = data.stream_name;
        let submatch = -1;

        let result = redisServer.getRedis("live_streams", data.stream_name);
        result.then(subscribers => {
            if (subscribers.length > 0) {
                let subscribersList = JSON.parse(subscribers);
                submatch = subscribersList.findIndex(sub => sub.user_id === data.user_id);
            }
            if (submatch === -1) {
                redisServer.pushRedis("live_streams", streamName, newSubscriber);
            }
        });

        let streamsQuery = DbModel.findOneAndUpdate({name: streamName},{ $inc: { viewers: 1 }});
        streamsQuery.exec(function(err, streamInfo) {
            if (!err) {
                _watchedStream(streamInfo._id,data.user_id,streamInfo.publisher_id);
            }
        });
    }
};

exports.unsubscribeStream = function(data) {
    if (data.user_id && data.stream_name) {
        let result = redisServer.getRedis("live_streams", data.stream_name);
        result.then(subscribers => {
            let subscribersList = JSON.parse(subscribers);
            const index = subscribersList.findIndex(x => x.user_id === data.user_id);
            if (index !== undefined && index !== -1) {
                subscribersList.splice(index, 1);
            }
            redisServer.updateRedis("live_streams", data.stream_name, subscribersList);
        });
    }
};

exports.popularCountries = function(req, res) {
    if(!req.params.filterBy)
    {
        return res.json(errMsg);
    }
    else
    {
        const streamCountries = [
        { "$match": {"mode" : "public" } },
        {
            $group: {
                _id: "$publisher_location",
                count: { $sum: 1 }
            },
        },
        { "$limit": (req.params.filterBy === "popular") ? 10 : 250 },

        {"$sort": {count:-1 } }

        ];

        DbModel.aggregate(streamCountries).exec(function(err, allpopularCountries) {
            if (!err && allpopularCountries.length > 0) {
                let popularCountries = [];
                allpopularCountries.forEach(function(popularCountry) {
                    popularCountries.push({ name: popularCountry._id, total: lib.abbreviateNumber(popularCountry.count)});
                });
                return res.json({ status: "true", result: popularCountries });          
            }
            else
            {   
                return res.json(errMsg);
            }
        });
    }
}

cron.schedule("* * * * *", () => {
    let streamsQuery = DbModel.find({
        live_status: "live"
    });
    streamsQuery.exec(function(err, allstreamInfo) {
        if (!err && allstreamInfo.length > 0) {
            allstreamInfo.forEach(function(streamInfo) {
                if(streamInfo){
                    if(streamInfo.name && streamInfo.publisher_id) { 
                        const options = {
                            url: config.ANTMEDIA.api_url + "broadcasts/" + streamInfo.name,
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Accept-Charset': 'utf-8',
                            }
                        };
                        request(options, function(err, res, body) {
                            if(!err) {
                                if(res.statusCode === 404){
                                     broadcastEnds(streamInfo.name, streamInfo.publisher_id);
                                }
                            }
                        });
                    }
                }
            });
        }
    });
});


cron.schedule("* * * * *", () => {
    let streamsQuery = DbModel.find({
        live_status: "recorded", playback_ready: "no"
    });
    streamsQuery.exec(function(err, allstreamInfo) {
        if (!err && allstreamInfo.length > 0) {
            allstreamInfo.forEach(function(streamInfo) {
                if(streamInfo){
                    if(streamInfo.name && streamInfo.publisher_id) { 
                        broadcastEnds(streamInfo.name, streamInfo.publisher_id, "recorded");
                    }
                }
            });
        }
    });
});

let _watchedStream = function(streamId, userId, publisherId) {
    if (streamId && userId) {
        WatchHistoryModel.countDocuments({ stream_id: streamId, user_id: userId },function(err, watchedCount) {
            if (!err && watchedCount) {
                if(watchedCount === 0){
                    let watchedHistory = new WatchHistoryModel({stream_id: mongoose.Types.ObjectId(streamId), user_id: mongoose.Types.ObjectId(userId),publisher: mongoose.Types.ObjectId(publisherId)});
                    watchedHistory.save(function(err, historyData) {
                        /*accountService.addWatchCount(userId,"update");*/
                    });
                }
            } 
        });
    }
};

let broadcastEnds = function(stream_name, publisher_id) {
    urlExists(config.ANTMEDIA.vod_url + stream_name + ".mp4", function(err, exists) {
        if(exists) {
            getVideoDurationInSeconds(config.ANTMEDIA.vod_url + stream_name + ".mp4").then((duration) => {
                let videoDuration = secondsToHms(duration);
                let streamsQuery = DbModel.findOneAndUpdate(
                {
                    publisher_id: publisher_id,
                    name: stream_name
                },
                {
                    live_status: "recorded",
                    playback_duration: videoDuration,
                    playback_ready: "yes",
                    playback_url: config.ANTMEDIA.vod_url + stream_name + ".mp4",
                    updated_at: moment().toISOString()
                }
                );
                streamsQuery.exec(function(err, streamInfo) {
                    if (!err) {
                    }
                });
            });
        }
    });
};

exports._liststreams = function(userID, callback) {
    DbModel.countDocuments(
    {
        publisher_id: userID
    },
    function(err, streamCount) {
        if (err) {
            callback(0);
        } else {
            callback(streamCount);
        }
    }
    );
};

exports.shareStream = function(req, res) {
    if (!req.body.user_id || !req.body.name || !req.body.users_list || !req.body.type) {
        return res.json(errMsg);
    } else {
        let streamsQuery = DbModel.findOne({
            name: req.body.name
        });
        streamsQuery.exec(function(err, streamInfo) {
            if (!err && streamInfo) {
                if(req.body.users_list)
                {
                    req.body.users_list = req.body.users_list.split(",");
                    req.body.users_list = req.body.users_list.filter(function (e) {return ( e != null && e != "");});
                    notificationService.sendNotifications(req.body.users_list,{scope:"streaminvitation",stream_name:req.body.name,type:req.body.type,message:"You're invited to watch the stream"});
                }
                return res.json({
                    status: "true",
                    message: "Shared successfully"
                });
            } else {
                return res.json(errMsg);
            }
        });
    }
};

let _deleteVod = function(stream_name) {
    const options = {
        url: config.ANTMEDIA.api_url + "vods/" + stream_name,
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Accept-Charset': 'utf-8',
        }
    };
    request(options, function(err, res, body) {
        if(err){
            /*console.log(err);*/ 
        }
        else
        {
            /*console.log(JSON.stringify(body));*/ 
        }
    });
};



let timeDuration = function(duration) {
    let diff = Number(duration) / 1000;

    let h = Math.floor(diff / 3600);
    let m = Math.floor((diff % 3600) / 60);
    let s = Math.floor((diff % 3600) % 60);

    let hDisplay = h > 0 ? h + ":" : "";
    let mDisplay = m > 0 ? m + ":" : "0:";
    let sDisplay = s > 0 ? s : "";
    return hDisplay + mDisplay + sDisplay;
};

let _notifyMyFollowers = function (publisherId,publisher,streamId) {

    let query = FollowingsModel.find({
        user_id: mongoose.Types.ObjectId(publisherId), 
    }).sort({ _id: -1 });

    query.exec(function(err, userlist) {
        let followers_list = [];
        if(userlist){
            userlist.forEach(function(userinfo) {
                if(userinfo.follower_id) {
                    followers_list.push(userinfo.follower_id);
                }
                notificationService.sendNotifications(followers_list,{scope:"followeronlive",stream_name:streamId,message:publisher + " is on live now."});
            });
        }

    });
}

/* FORMAT BROADCAST TIME */
let secondsToHms = function(d) {
    let sec_num = parseInt(d);
    let hours   = Math.floor(sec_num / 3600);
    let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    let seconds = sec_num - (hours * 3600) - (minutes * 60);        
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    if(hours === "00")
    {
        return minutes+':'+seconds;
    }
    else
    {
        return hours+':'+minutes+':'+seconds;
    }
}

/* HASH-BASED TOKEN */
let _hashAuthToken = function(streamId,streamRole){
    const hashToken = streamId + streamRole + 'authAntMedia@2020$';
    const hash = crypto.createHash('sha256').update(hashToken).digest('hex');
    return hash;
}




