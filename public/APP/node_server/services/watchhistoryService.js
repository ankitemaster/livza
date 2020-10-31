const config = require("../config/keys");
const mongoose = require("mongoose");

let DbModel = require("../models/watchhistoryModel");
let streamModel = require("../models/streamModel");

let accountService = require("../services/accountService");

exports.listWatchHistory = function(req, res) {
    if (!req.params.userId) {
        return res.json(errMsg);
    } else {
        let offset = 0;
        let limit = 20;
        if (req.params.limit) {
            limit = parseInt(req.params.limit);
        }
        if (req.params.offset) {
            offset = parseInt(req.params.offset);
        }
        let SearchQuery = DbModel.find({"user_id" : req.params.userId })
        .populate("publisher")
        .populate("stream_id")
        .sort({"created_at" : -1})
        .skip(offset)
        .limit(limit);
        SearchQuery.exec(function(err, allstreamInfo) {
            if (!err && allstreamInfo.length > 0) {
                let streamResult = [];
                allstreamInfo.forEach(function(streamInfo) {
                    if(streamInfo.stream_id){
                        streamResult.push({
                        name: streamInfo.stream_id.name,
                        mode: streamInfo.stream_id.mode,
                        title: streamInfo.stream_id.title,
                        watch_count: streamInfo.stream_id.viewers,
                        streamed_on: streamInfo.stream_id.created_at,
                        stream_id: streamInfo.stream_id._id,
                        publisher_id: streamInfo.publisher._id,
                        publisher_image: config.IMAGE_BASE_URL + "accounts/" + streamInfo.publisher.acct_photo,
                        stream_thumbnail: config.IMAGE_BASE_URL + "streams/" + streamInfo.thumbnail,
                        posted_by: streamInfo.publisher.acct_name,
                        type: streamInfo.stream_id.live_status,
                        likes: streamInfo.stream_id.likes,
                        playback_duration: streamInfo.stream_id.playback_duration,
                        playback_ready: streamInfo.stream_id.playback_ready,
                        playback_url: streamInfo.stream_id.playback_url
                    });
                    }
                });
                
                return res.json({ status: "true", result: streamResult });
            } else {
                return res.json({ status: "false", message: "No data found :)" });
            }
        });
    }
};

exports.clearWatchHistory = function(req, res) {
  if (!req.params.userId) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
  });
} else {
    DbModel.countDocuments(
    {
        user_id: req.params.userId
    },
    function(err, historyCount) {
        if (historyCount > 0) {
          DbModel.deleteMany(
          {
              user_id: req.params.userId
          },
          function(err, watchhistoryCount) {
            accountService.addWatchCount(req.params.userId,"delete");
            return res.json({
                status: "true",
                message: "Deleted successfully"
            });
        }
        );
      } else {
          return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
      }
  }
  );
}
};

exports.updateWatchCount = function(req, res) {
    if (!req.body.stream_id || !req.body.user_id) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    }
    else
    {   
        let query = streamModel.findOne({"_id": req.body.stream_id});
        query.exec(function(err, streamDetails) {
            if(streamDetails)
            {
                DbModel.countDocuments({ stream_id: mongoose.Types.ObjectId(req.body.stream_id), user_id: mongoose.Types.ObjectId(req.body.user_id) },function(err, watchedCount) {
                    if (!err) {
                        if(watchedCount === 0){
                            let watchedHistory = new DbModel({stream_id: mongoose.Types.ObjectId(req.body.stream_id), user_id: mongoose.Types.ObjectId(req.body.user_id), publisher:mongoose.Types.ObjectId(streamDetails.publisher_id)});
                            watchedHistory.save(function(err, historyData) {
                                if(!err){
                                    accountService.addWatchCount(req.body.user_id,"update");
                                    return res.json({
                                        status: "true",
                                        message: "Watch count updated successfully"
                                    });
                                }
                                else{
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
                                status: "true",
                                message: "Watch count updated successfully"
                            });
                        }
                    }
                    else
                    {  
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
                    message: "Something went to be wrong"
                });
            }
        });
    }
}

exports._clearWatchHistory = function(streamId) {
    DbModel.countDocuments({ stream_id: streamId },function(err, historyCount) {
        if (historyCount > 0) {
            DbModel.deleteMany({ stream_id: streamId },function(err, watchhistoryCount) {
            });
        }
    });
};