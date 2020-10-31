let dbModel = require("../models/followingModel");
let notificationService = require("./notificationService");
let accountService = require("../services/accountService");

let moment = require("moment");
let mongoose = require("mongoose");

exports.FollowPeople = function(req, res) {
    if (!req.body.user_id || !req.body.follower_id || !req.body.type) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        if (req.body.type === "Follow") {
            FollowPeople(req.body.user_id, req.body.follower_id, res);
        }

        if (req.body.type === "Unfollow") {
            UnfollowPeople(req.body.user_id, req.body.follower_id, res);
        }
    }
};

exports.AccountFollowings = function(req, res) {
    if (!req.params.userId || !req.params.limit || !req.params.offset) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let offset = 0;
        let limit = 20;

        if (req.params.limit) {
            limit = parseInt(req.params.limit);
        }

        if (req.params.offset) {
            offset = parseInt(req.params.offset);
        }

        let query = dbModel
            .countDocuments({
                follower_id: req.params.userId
            })
            .skip(offset)
            .limit(limit);

        query.exec(function(err, count) {
            if (count > 0) {
                dbModel
                    .find({
                        follower_id: req.params.userId
                    })
                    .populate("user_id")
                    .limit(limit)
                    .skip(offset)
                    .sort({
                        _id: -1
                    })
                    .exec(function(err, userlist) {
                        let followers_list = [];
                        userlist.forEach(function(userinfo) {
                            let birthdate = moment(userinfo.follower_id.acct_birthday).format("DD/MM/YYYY");
                            followers_list.push({
                                user_id: userinfo.user_id._id,
                                user_image: userinfo.user_id.acct_photo,
                                name: userinfo.user_id.acct_name,
                                age: userinfo.user_id.acct_age,
                                dob: birthdate,
                                gender: userinfo.user_id.acct_gender,
                                premium_member: userinfo.user_id.acct_membership == "sub" ? "true" : "false",
                                location: userinfo.user_id.acct_location,
                                login_id:
                                    userinfo.user_id.acct_sync == "phonenumber"
                                        ? userinfo.user_id.acct_phoneno
                                        : userinfo.user_id.acct_facebookid,
                                privacy_age: userinfo.user_id.acct_show_age,
                                privacy_contactme: userinfo.user_id.acct_show_contactme,
                                show_notification: userinfo.user_id.acct_alert,
                                chat_notification: userinfo.user_id.acct_chat_alert,
                                follow_notification: userinfo.user_id.acct_follow_alert,
                            });
                        });
                        return res.json({
                            status: "true",
                            users_list: followers_list
                        });
                    });
            } else {
                return res.json({
                    status: "false",
                    message: "No records found"
                });
            }
        });
    }
};

exports.AccountFollowers = function(req, res) {
    if (!req.params.userId || !req.params.limit || !req.params.offset) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let offset = 0;
        let limit = 20;

        if (req.params.limit) {
            limit = parseInt(req.params.limit);
        }

        if (req.params.offset) {
            offset = parseInt(req.params.offset);
        }

        let query = dbModel
            .countDocuments({
                user_id: req.params.userId
            })
            .skip(offset)
            .limit(limit);

        query.exec(function(err, count) {
            if (count > 0) {
                dbModel
                    .find({
                        user_id: req.params.userId
                    })
                    .populate("follower_id")
                    .limit(limit)
                    .skip(offset)
                    .sort({
                        _id: -1
                    })
                    .exec(function(err, userlist) {
                        let followers_list = [];
                        userlist.forEach(function(userinfo) {
                            let birthdate = moment(userinfo.follower_id.acct_birthday).format("DD/MM/YYYY");
                            followers_list.push({
                                user_id: userinfo.follower_id._id,
                                user_image: userinfo.follower_id.acct_photo,
                                name: userinfo.follower_id.acct_name,
                                age: userinfo.follower_id.acct_age,
                                dob: birthdate,
                                gender: userinfo.follower_id.acct_gender,
                                premium_member: userinfo.follower_id.acct_membership == "sub" ? "true" : "false",
                                location: userinfo.follower_id.acct_location,
                                login_id:
                                    userinfo.follower_id.acct_sync == "phonenumber"
                                        ? userinfo.follower_id.acct_phoneno
                                        : userinfo.follower_id.acct_facebookid,
                                privacy_age: userinfo.follower_id.acct_show_age,
                                privacy_contactme: userinfo.follower_id.acct_show_contactme,
                                show_notification: userinfo.follower_id.acct_alert,
                                chat_notification: userinfo.follower_id.acct_chat_alert,
                                follow_notification: userinfo.follower_id.acct_follow_alert,
                            });
                        });
                        return res.json({
                            status: "true",
                            users_list: followers_list
                        });
                    });
            } else {
                return res.json({
                    status: "false",
                    message: "No records found"
                });
            }
        });
    }
};

exports._followings = function(userID, callback) {
    dbModel.countDocuments(
        {
            follower_id: userID
        },
        function(err, followingCount) {
            if (err) {
                callback(0);
            } else {
                callback(followingCount);
            }
        }
    );
};

exports._followers = function(userID, callback) {
    dbModel.countDocuments(
        {
            user_id: userID
        },
        function(err, followingCount) {
            if (err) {
                callback(0);
            } else {
                callback(followingCount);
            }
        }
    );
};

exports._followingsCount = function(user) {
    return new Promise(function(resolve, reject) {
        dbModel.countDocuments(
            {
                follower_id: user
            },
            function(err, searchCount) {
                if (!err) {
                    resolve(searchCount);
                } else {
                    reject(err);
                }
            }
        );
    });
};

exports._followersCount = function(user) {
    let searchQuery = dbModel.countDocuments({
        user_id: user
    });
    return new Promise(function(resolve, reject) {
        searchQuery.exec(function(err, searchCount) {
            if (!err) {
                resolve(searchCount);
            } else {
                reject(err);
            }
        });
    });
};

exports._followingCount = function(user, fuser) {
    let searchQuery = dbModel.countDocuments({
        user_id: user,
        follower_id: fuser
    });
    return new Promise(function(resolve, reject) {
        searchQuery.exec(function(err, usersList) {
            if (!err && usersList != null && typeof usersList != "undefined") {
                resolve(usersList);
            } else {
                reject(err);
            }
        });
    });
};

exports._verifyfollowings = function(followingslist, fuserid) {
    let searchQuery = dbModel.countDocuments({
        user_id: userid,
        follower_id: fuserid
    });
    return new Promise(function(resolve, reject) {
        searchQuery.exec(function(err, followingCount) {
            if (!err) {
                resolve(followingCount);
            } else {
                reject(err);
            }
        });
    });
};

exports._validGift = function(gift_id, gems_count) {
    let giftsQuery = dbModel.find({ _id: gift_id, gft_gems: gems_count }).limit(1);
    return new Promise(function(resolve, reject) {
        giftsQuery.exec(function(err, gift_details) {
            if (!err && gift_details != null && typeof gift_details != "undefined") {
                resolve(gift_details);
            } else {
                reject(err);
            }
        });
    });
};

exports._isfollowing = function(userid, fuserid, callback) {
    dbModel.countDocuments(
        {
            user_id: userid,
            follower_id: fuserid
        },
        function(err, followingCount) {
            if (followingCount > 0 && !err) {
                callback("true");
            } else {
                callback("false");
            }
        }
    );
};

exports.MutualFollowers = function(req, res) {
    if (!req.body.user_id || !req.body.limit || !req.body.offset) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let offset = 0;
        let limit = 20;
        let searchString = "";

        if (req.body.limit) {
            limit = parseInt(req.body.limit);
        }

        if (req.body.offset) {
            offset = parseInt(req.body.offset);
        }

        let query = dbModel.find({
            follower_id: mongoose.Types.ObjectId(req.body.user_id),
            mutual_follow : 1,
        }).populate("user_id").skip(offset).limit(limit).sort({
            _id: -1
        });

        if(req.body.search_key) {
            query =dbModel.find({
                follower_id: mongoose.Types.ObjectId(req.body.user_id),
                mutual_follow : 1,
            }).populate({path: 'user_id',match: {
                acct_name:{ $regex: req.body.search_key, $options: "i" }
            }}).limit(limit)
            .skip(offset)
            .sort({
                _id: -1
            });
        }

        query.exec(function(err, userlist) {
            let followers_list = [];
            userlist.forEach(function(userinfo) {
                if(userinfo.user_id){
                  let birthdate = moment(userinfo.user_id.acct_birthday).format("DD/MM/YYYY");
                  followers_list.push({
                    user_id: userinfo.user_id._id,
                    user_image: userinfo.user_id.acct_photo,
                    name: userinfo.user_id.acct_name,
                    age: userinfo.user_id.acct_age,
                    dob: birthdate,
                    gender: userinfo.user_id.acct_gender,
                    premium_member: userinfo.user_id.acct_membership == "sub" ? "true" : "false",
                    location: userinfo.user_id.acct_location,
                    login_id:userinfo.user_id.acct_sync == "phonenumber"? userinfo.user_id.acct_phoneno : userinfo.user_id.acct_facebookid,
                    privacy_age: userinfo.user_id.acct_show_age,
                    privacy_contactme: userinfo.user_id.acct_show_contactme,
                });  
              }
            });
            
            if(followers_list.length >0)
            {
                return res.json({
                    status: "true",
                    users_list: followers_list
                });
            }
            else
            {
                return res.json({
                    status: "false",
                    message: "No records found"
                });
            }
        });
    }
}

let FollowPeople = function(user, followuser, res) {
    dbModel.countDocuments(
    {
        user_id: user,
        follower_id: followuser
    },
    function(err, count) {
        if (count > 0) {
            return res.json({
                status: "true",
                message: "Followed successfully"
            });
        } else {
            dbModel.countDocuments({user_id: followuser,follower_id: user},function(followerr, mutualStatus) {
                if(!followerr) {
                    let newPeoples = new dbModel({
                        user_id: user,
                        follower_id: followuser,
                        mutual_follow : (mutualStatus > 0) ? 1 : 0
                    });
                    newPeoples.save(function(err) {
                        if (!err) {
                            matchMutual(followuser, user);
                            followNotification(user, followuser);
                            accountService.profileLikes(user, followuser);
                            return res.json({
                                status: "true",
                                message: "Followed successfully"
                            });
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
                        message: "Something went to be wrong"
                    });
                }
            });
        }
    });
}
      

let UnfollowPeople = function(user, followuser, res) {
    dbModel.countDocuments(
        {
            user_id: user,
            follower_id: followuser
        },
        function(err, count) {
            if (count > 0) {
                dbModel.findOneAndRemove(
                    {
                        user_id: user,
                        follower_id: followuser
                    },
                    function(err, count) {
                        if (!err) {
                            accountService.profiledisLikes(user, followuser);
                            unmatchMutual(followuser,user);
                            return res.json({
                                status: "true",
                                message: "Unfollowed successfully"
                            });
                        } else {
                            return res.json({
                                status: "false",
                                message: "Something went to be wrong"
                            });
                        }
                    }
                );
            } else {
                return res.json({
                    status: "true",
                    message: "Unfollowed successfully"
                });
            }
        }
    );
};

let followNotification = function(user, followuser) {
    let messageData = {};
    let followings = [];
    accountService._accountDetails(followuser, function(followuser_info) {
        if (followuser_info) {
            accountService._accountDetails(user, function(user_info) {
                if (user_info.acct_follow_alert) {
                    let followedby = followuser_info.acct_name;
                    messageData.scope = "follow";
                    messageData.message = followedby + " following you";
                    messageData.user_id = followuser_info._id;
                    followings.push(user);
                    notificationService.sendNotification(followings, messageData);
                }
            });
        }
    });
};

let matchMutual = function(followuser, user) {
    dbModel.findOneAndUpdate({user_id: followuser,follower_id: user}, {mutual_follow : 1}, { new: true }, function (error, follow_details) {
    });
};

let unmatchMutual =  function(followuser, user) {
    dbModel.findOneAndUpdate({user_id: followuser,follower_id: user}, {mutual_follow : 0}, { new: true }, function (error, follow_details) {
    });
};


