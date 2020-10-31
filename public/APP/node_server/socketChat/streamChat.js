"use strict";
const express = require("express");
const app = express();
const config = require("../config/keys");
const fs = require("fs");
const async = require("async");
const cron = require("node-cron");
let server;
let secure = config.HTTPS;

/* models */
const DbModel = require("../models/streamModel");
const ReportsModel = require("../models/streamreportModel");
const FollowingsModel = require("../models/followingModel");
const AccountsModel = require("../models/accountModel");

/* services */
let redisServer = require("../services/redisService");
let giftService = require("../services/giftService");
let accountService = require("../services/accountService");

/* Create HTTP server. */
if (secure === "off") {
    server = require("http").createServer(app);
} else {
    /* ssl certificates */
    let privateKey = fs.readFileSync("");
    let certificate = fs.readFileSync("");
    let ca = fs.readFileSync("");
    let sslOptions = {
        key: privateKey,
        cert: certificate,
        ca: ca
    };
    server = require("https").createServer(sslOptions, app);
}

const io = require("socket.io").listen(server);

server.listen(config.LIVE_BROADCAST_PORT, function() {
    console.log("socket.io (live-broadcast-chat) is running on " + config.LIVE_BROADCAST_PORT);
});

/* services */
const streamService = require("../services/streamService");

io.sockets.on("connection", function(socket) {
    console.log("a client connected");

    /* PUBLISH A STREAM */
    socket.on("_publishStream", function(data) {
        socket.join(data.stream_name);
    });

    /* JOIN A STREAM */
    socket.on("_subscribeStream", function(data) {
        socket.join(data.stream_name);
        streamService.subscribeStream(data);
    });

    /* LEAVE A STREAM */
    socket.on("_unsubscribeStream", function(data) {
        /* console.log("unsubscribeStream" + JSON.stringify(data)); */
        socket.broadcast.to(data.stream_name).emit("_subscriberLeft", data);
        streamService.unsubscribeStream(data);
    });

    /* INSTANT STREAM DETAILS */
    socket.on("_getstreamInfo", function(data) {
        let user_id = data.user_id;
        let tasks = [
        function generalDetails(cb) {
            let streamsQuery = DbModel.findOne({
                name: data.stream_name
            }).populate("publisher_id");
            streamsQuery.exec(function(err, streamInfo) {
                if (!err && streamInfo) {
                    let result = {};
                    result.status = "true";
                    result.stream_id = streamInfo._id;
                    result.name = streamInfo.name;
                    result.mode = streamInfo.mode;
                    result.group_members = streamInfo.group_members;
                    result.title = streamInfo.title;
                    result.posted_by = streamInfo.publisher_id.acct_name;
                    result.publisher_id = streamInfo.publisher_id._id;
                    result.likes = streamInfo.likes.toString();
                    result.stream_thumbnail = config.IMAGE_BASE_URL + "streams/" + streamInfo.thumbnail;
                    result.publisher_image =
                    config.IMAGE_BASE_URL + "accounts/" + streamInfo.publisher_id.acct_photo;
                    let dataresult = redisServer.getRedis("live_streams", streamInfo.name);
                    dataresult.then(subscribers => {
                        result.live_viewers = [];
                        result.watch_count = "0";
                        if (Object.keys(subscribers).length !== "0") {
                            let subscribersList = JSON.parse(subscribers);
                            subscribersList.reverse();
                            subscribersList.slice(0, 10);
                            result.live_viewers = subscribersList;
                            result.watch_count = subscribersList.length.toString();
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
                socket.emit("_streamInfo", results);
            }
        });
    });

    /* MSG ON STREAM */
    socket.on("_sendMsg", function(data) {
        if (data.type === "liked") {
            streamService.postLikes(data);
        }
       
        if (data.type === "gift") {
            streamService.giftStream(data);
        }

        streamService.commentStream(data);
        socket.broadcast.to(data.stream_name).emit("_msgReceived", data);
    });

    /* SEND GIFT */
    socket.on("_sendGift", function(data) {
        _giftProgress(
            data.gift_from,
            data.gift_to,
            data.gift_id,
            data.gems_count,
            data.gems_earnings,
            data.gift_title,
            data.gift_icon
            );
    });

    /* USER LEFT */
    socket.on("disconnect", function(data) {
        console.log("the user has left");
        socket.leave(data.room);
    });

    async function _giftProgress(gift_from, gift_to, gift_id, gems_count, gems_earnings, gift_title, gift_icon) {
        try {
            const query1 = await giftService._validGift(gift_id, gems_count).catch(error => {
                throw new _giftError({
                    message: "query1 failed",
                    from: gift_from,
                    error: error
                });
            });

            const query2 = await accountService._sufficientGems(gift_from, gems_count).catch(error => {
                throw new _giftError({
                    message: "query2 failed",
                    from: gift_from,
                    error: error
                });
            });

            const query3 = await accountService._updateGems(gift_from, gems_count, "debit").catch(error => {
                throw new _giftError({
                    message: "query3 failed",
                    from: gift_from,
                    error: error
                });
            });

            const query4 = await accountService._updateGifts(gift_to, gems_earnings, "credit").catch(error => {
                throw new _giftError({
                    message: "query4 failed",
                    from: gift_from,
                    error: error
                });
            });

            if (query1 && query2) {
                socket.emit("_giftStatus", {
                    status: "true",
                    total_gems: query3
                });
            }
        } catch (error) {
            if (!(error instanceof _giftError)) {
                /* console.log(error); */
            }
        }
    }

    function _giftError(errdata) {
        let errmsg = errdata.message;
        if (errmsg == "query2 failed") {
            socket.emit("_giftStatus", {
                status: "false",
                message: "out of gems"
            });
        } else {
            socket.emit("_giftStatus", {
                status: "error",
                message: "failed try again later"
            });
        }
    }

    cron.schedule("* * * * *", () => {
        let streamsQuery = DbModel.find({
            live_status: "live",
            active_status: 1
        });
        streamsQuery.exec(function(err, allstreamInfo) {
            if (!err && allstreamInfo.length > 0) {
                allstreamInfo.forEach(function(streamInfo) {
                    let data = { message: "The stream has been blocked by admin..." };
                    socket.broadcast.to(streamInfo.name).emit("_streamBlocked", data);
                });
            } else {
                /*console.log("No streams are blocked now");*/
            }
        });
    });
});
