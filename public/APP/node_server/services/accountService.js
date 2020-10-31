const path = require("path");
const moment = require("moment");
const jwt = require("jsonwebtoken");
const config = require("../config/keys");
const fs = require("fs");
const randomstring = require("randomstring");

/* models */
let DbModel = require("../models/accountModel");
let settingModel = require("../models/settingModel");
let streamModel = require("../models/streamModel");

let followingService = require("../services/followingService");
let deviceService = require("../services/deviceService");
let chatService = require("../services/chatService");
let streamService = require("../services/streamService");

exports.accountLogin = function(req, res) {
    if (!req.body.type || !req.body.login_id) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let searchBy = {};
        searchBy = {
            acct_sync: "phonenumber",
            acct_phoneno: req.body.login_id
        };

        if (req.body.type == "facebook") {
            searchBy = {
                acct_sync: "facebook",
                acct_facebookid: req.body.login_id
            };
        }
        if (req.body.type == "apple") {
            searchBy = {
                acct_sync: "apple",
                acct_appleid: req.body.login_id
            };
        }

        DbModel.countDocuments(searchBy, function(err, isAccount) {
            if (isAccount > 0) {
                let query = DbModel.findOne(searchBy);
                query.select({
                    __v: 0,
                    acct_createdat: 0,
                    acct_sync: 0
                });
                query.exec(function(err, accountdetails) {
                    if (!err) {
                        let accountactive = accountdetails.acct_status;
                        if (accountactive == 0) {
                            return res.json({
                                status: "accountblocked",
                                message: "Your account will be active in 24 hrs. For details Kindly contact admin."
                            });
                        } else {
                            let birthdate = moment(accountdetails.acct_birthday).format("DD/MM/YYYY");
                            let signintoken = jwt.sign(accountdetails.toObject(), config.JWT_SECRET, {});
                            return res.json({
                                status: "true",
                                accountexists: "true",
                                user_id: accountdetails._id,
                                name: accountdetails.acct_name,
                                age: accountdetails.acct_age,
                                dob: birthdate,
                                gender: accountdetails.acct_gender,
                                login_id: req.body.login_id,
                                auth_token: signintoken,
                                user_image: accountdetails.acct_photo,
                                available_gems: accountdetails.acct_gems,
                                available_gifts: accountdetails.acct_gifts,
                                premium_member: accountdetails.acct_membership == "sub" ? "true" : "false",
                                premium_expiry_date: moment(accountdetails.acct_membership_till).format("DD/MM/YYYY"),
                                location: accountdetails.acct_location,
                                privacy_age: accountdetails.acct_show_age,
                                privacy_contactme: accountdetails.acct_show_contactme,
                                show_notification: accountdetails.acct_alert,
                                chat_notification: accountdetails.acct_chat_alert,
                                follow_notification: accountdetails.acct_follow_alert,
                            });
                        }
                    } else {
                        return res.json({
                            status: "false",
                            message: "Something went to be wrong"
                        });
                    }
                });
            } else {
                return res.json({
                    status: "true",
                    accountexists: "false"
                });
            }
        });
    }
};

exports.accountSignup = function(req, res) {
    if (!req.body.name || !req.body.age || !req.body.dob || !req.body.gender || !req.body.login_id || !req.body.type || !req.body.user_name || !req.body.location) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let searchBy = {};
        searchBy = {
            acct_sync: "phonenumber",
            acct_phoneno: req.body.login_id
        };

        if (req.body.type == "facebook") {
            searchBy = {
                acct_sync: "facebook",
                acct_facebookid: req.body.login_id,
                acct_mailid: req.body.mail_id
            };
        }
        if (req.body.type == "apple") {
            searchBy = {
                acct_sync: "apple",
                acct_appleid: req.body.login_id,
                acct_mailid: req.body.mail_id
            };
        }
        DbModel.countDocuments(searchBy, function(err, isAccount) {
            if (isAccount > 0) {
                let query = DbModel.findOne(searchBy);
                query.select({
                    __v: 0,
                    acct_createdat: 0,
                    acct_sync: 0
                });
                query.exec(function(err, accountdetails) {
                    if (!err) {
                        let birthdate = moment(accountdetails.acct_birthday).format("DD/MM/YYYY");
                        let signintoken = jwt.sign(accountdetails.toObject(), config.JWT_SECRET);
                        return res.json({
                            status: "true",
                            user_id: accountdetails._id,
                            user_name: accountdetails.acct_username,
                            location: accountdetails.acct_location,
                            name: accountdetails.acct_name,    
                            age: accountdetails.acct_age,
                            dob: birthdate,
                            gender: accountdetails.acct_gender,
                            login_id: req.body.login_id,
                            auth_token: signintoken,
                            privacy_age: accountdetails.acct_show_age,
                            privacy_contactme: accountdetails.acct_show_contactme,
                            show_notification: accountdetails.acct_alert,
                            chat_notification: accountdetails.acct_chat_alert,
                            follow_notification: accountdetails.acct_follow_alert
                        });
                    } else {
                        return res.json({
                            status: "false",
                            message: "Something went to be wrong"
                        });
                    }
                });
            } else {

                let checkUsername = {
                    acct_username: req.body.user_name
                };

                DbModel.countDocuments(checkUsername, function (err, isUsername) {
                    if (isUsername > 0) {
                        return res.json({
                            status: "false",
                            message: "Username is taken. Try another."
                        });
                    }
                    else {
                        let registeraccount = {};
                        let dob = moment(req.body.dob, "DD/MM/YYYY").toDate();
                        birthdate = moment(dob).toISOString();
                        registeraccount.acct_name = req.body.name;
                        registeraccount.acct_age = req.body.age;
                        registeraccount.acct_birthday = birthdate;
                        registeraccount.acct_gender = req.body.gender;
                        registeraccount.acct_photo = " "; /* Save Image as empty */
                        registeraccount.acct_username = req.body.user_name;
                        registeraccount.acct_location = req.body.location;
                        if (req.body.type == "facebook") {
                            registeraccount.acct_facebookid = req.body.login_id;
                            registeraccount.acct_sync = "facebook";
                            registeraccount.acct_mailid = req.body.mail_id;
                        } else {
                            if (req.body.type == "apple") {
                                registeraccount.acct_appleid = req.body.login_id;
                                registeraccount.acct_sync = "apple";
                                registeraccount.acct_mailid = req.body.mail_id;
                            }
                            else{
                                registeraccount.acct_phoneno = req.body.login_id;
                                registeraccount.acct_sync = "phonenumber";
                            }
                        }
                        registeraccount.acct_referral_code = randomstring.generate(8);
                        let newAccount = new DbModel(registeraccount);
                        newAccount.save(function(err, accountdetails) {
                            if (!err) {
                                let birthdate = moment(accountdetails.acct_birthday).format("DD/MM/YYYY");
                                let signintoken = jwt.sign(accountdetails.toObject(), config.JWT_SECRET);
                                _signupCredits(accountdetails._id);
                                return res.json({
                                    status: "true",
                                    user_id: accountdetails._id,
                                    user_name: accountdetails.acct_username,
                                    location: accountdetails.acct_location,
                                    name: accountdetails.acct_name,
                                    age: accountdetails.acct_age,
                                    dob: birthdate,
                                    gender: accountdetails.acct_gender,
                                    login_id: req.body.login_id,
                                    auth_token: signintoken,
                                    privacy_age: accountdetails.acct_show_age,
                                    privacy_contactme: accountdetails.acct_show_contactme,
                                    show_notification: accountdetails.acct_alert,
                                    chat_notification: accountdetails.acct_chat_alert,
                                    follow_notification: accountdetails.acct_follow_alert
                                });
                            } else {
                                return res.json({
                                    status: "false",
                                    message: "Something went to be wrong"
                                });
                            }
                        });

                    }
                });

            }
        });
}
};

exports.accountProfile = function(req, res) {
    if (!req.body.user_id || !req.body.profile_id) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let user_account = req.body.profile_id;
        let update_userinfo = {};
        if (req.body.name) {
            update_userinfo.acct_name = req.body.name;
        }
        if (req.body.privacy_age) {
            update_userinfo.acct_show_age = req.body.privacy_age;
        }
        if (req.body.privacy_contactme) {
            update_userinfo.acct_show_contactme = req.body.privacy_contactme;
        }
        if (req.body.show_notification) {
            update_userinfo.acct_alert = req.body.show_notification;
        }
        if (req.body.chat_notification) {
            update_userinfo.acct_chat_alert = req.body.chat_notification;
        }
        if (req.body.follow_notification) {
            update_userinfo.acct_follow_alert = req.body.follow_notification;
        }
        if (req.body.location) {
            update_userinfo.acct_location = req.body.location;
        }
        if (req.body.paypal_id || req.body.paypal_id == "") {
            update_userinfo.acct_payment_id = req.body.paypal_id;
        }
        let searchQuery = { publisher_id:req.body.profile_id, mode: "public" };
        if(req.body.user_id === req.body.profile_id){
            searchQuery = { publisher_id:req.body.profile_id };
        }
        let searchBy = {
            _id: user_account
        };
        DbModel.countDocuments(searchBy, function(err, isAccount) {
            if (isAccount > 0) {
                DbModel.findByIdAndUpdate(
                    searchBy,
                    update_userinfo,
                    {
                        new: true
                    },
                    (error, accountdetails) => {
                        if (error) {
                            return res.json({
                                status: "false",
                                message: "Something went to be wrong"
                            });
                        } else {
                            let streamsQuery = streamModel.countDocuments(searchQuery);
                            streamsQuery.exec(function(err, streamsCount) {
                                followingService._isfollowing(user_account, req.body.user_id, function(followstatus) {
                                    chatService._isBlocked(req.body.user_id, user_account, function(blockStatus) {
                                        chatService._isBlocked(user_account, req.body.user_id, function(blockedStatus) {
                                            let birthdate = moment(accountdetails.acct_birthday).format("DD/MM/YYYY");
                                            return res.json({
                                                status: "true",
                                                blocked_by_me: blockStatus == 0 ? "true" : "false",
                                                blocked_me: blockedStatus == 0 ? "true" : "false",
                                                user_id: accountdetails._id,
                                                name: accountdetails.acct_name,
                                                user_name: accountdetails.acct_username,
                                                age: accountdetails.acct_age,
                                                dob: birthdate,
                                                gender: accountdetails.acct_gender,
                                                login_id: req.body.login_id,
                                                user_image: accountdetails.acct_photo,
                                                paypal_id: accountdetails.acct_payment_id,
                                                followers: accountdetails.acct_followers_count,
                                                followings: accountdetails.acct_followings_count,
                                                follow: followstatus,
                                                available_gems: accountdetails.acct_gems,
                                                videos_count: streamsCount,
                                                watched_count: accountdetails.acct_watched_count,
                                                available_gifts: accountdetails.acct_gifts,
                                                gift_earnings: accountdetails.acct_gift_earnings,
                                                premium_member: accountdetails.acct_membership == "sub" ? "true" : "false",
                                                premium_expiry_date: accountdetails.acct_membership_till,
                                                location: accountdetails.acct_location,
                                                privacy_age: accountdetails.acct_show_age,
                                                privacy_contactme: accountdetails.acct_show_contactme,
                                                show_notification: accountdetails.acct_alert,
                                                chat_notification: accountdetails.acct_chat_alert,
                                                created_at: accountdetails.acct_createdat,
                                                follow_notification: accountdetails.acct_follow_alert,
                                                unread_broadcasts: accountdetails.acct_unread_broadcasts,
                                                referal_link: "appstore?referal_code=" + accountdetails.acct_referral_code,
                                            });
                                        });
                                    });
                                });
                            });
                        }
                    });
} else {
    return res.json({
        status: "false",
        message: "No account found !"
    });
}
});
}
};

exports._updateProfile = function(user_id, profile_pic) {
    DbModel.findOne({ _id: user_id }, function(err, account_details) {
        if (!err) {
            let account_pic = account_details.acct_photo;
            let image_path = path.join(__dirname, config.ASSETS + "/accounts/" + account_pic);
unlinkPic(image_path); // remove the account's old pic
DbModel.findByIdAndUpdate(
{
    _id: user_id
},
{
    acct_photo: profile_pic
},
{},
(err, doc) => {
    if (err) {
// console.log(err);
}
}
);
} else {
// console.log(err);
}
});
};

exports._updateChatPic = function(user_id, recent_snap) {
    DbModel.findOne({ _id: user_id }, function(err, account_details) {
        if (!err) {
            let chat_pic = account_details.acct_chat_snap;
            let image_path = path.join(__dirname, config.ASSETS + "/randomchats/" + chat_pic);
unlinkPic(image_path); // remove the account's old chat snap
DbModel.findByIdAndUpdate(
{
    _id: user_id
},
{
    acct_chat_snap: recent_snap
},
{},
(err, doc) => {
    if (err) {
// console.log(err);
}
}
);
} else {
// console.log(err);
}
});
};

/* DELETE PROFILE IMAGE FILE */
unlinkPic = function(imagepath) {
    fs.unlink(imagepath, function(err) {
        if (!err) {
            /* console.log("File deleted!"); */
        } else {
/* console.log(err);
console.log("File not deleted!"); */
}
});
};

exports._updateGems = function(account_id, gems, filltype) {
    let updatedata;
    let refill_gems = parseInt(gems);

    if (filltype == "credit") {
        updatedata = { $inc: { acct_gems: refill_gems } };
    }

    if (filltype == "debit") {
        updatedata = { $inc: { acct_gems: refill_gems * -1 } };
    }

    return new Promise(function(resolve, reject) {
        DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(error, acc_details) {
            if (error) {
                reject(err);
            } else {
                resolve(acc_details.acct_gems);
            }
        });
    });
};

exports._updateGifts = function(account_id, gems, filltype) {
    let updatedata;

    let refill_gems = parseInt(gems);

    if (filltype == "credit") {
        updatedata = {
            $inc: { acct_gifts: 1, acct_gift_earnings: refill_gems }
        };
    }

    if (filltype == "debit") {
        updatedata = { $inc: { acct_gifts: -1 } };
    }

    return new Promise(function(resolve, reject) {
        DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(err, acc_details) {
            if (err) {
                reject(err);
            } else {
                resolve(acc_details.acct_gifts);
            }
        });
    });
};

exports._grantMembership = function(membership, member, callback) {
    let result = {};
    let refill_gems = parseInt(membership.gems);

    let updatedata = { $inc: { acct_gems: refill_gems } };
    updatedata.acct_membership = "sub";
    if (membership.validity == "1M") {
        updatedata.acct_membership_till = moment().add(1, "months");
    }

    if (membership.validity == "3M") {
        updatedata.acct_membership_till = moment().add(3, "months");
    }

    if (membership.validity == "6M") {
        updatedata.acct_membership_till = moment().add(6, "months");
    }

    if (membership.validity == "1Y") {
        updatedata.acct_membership_till = moment().add(1, "years");
    }

    DbModel.findOneAndUpdate({ _id: member }, updatedata, { new: true }, function(err, acc_details) {
        if (!err) {
            result.status = true;
            result.details = acc_details;
            callback(result);
        } else {
            result.status = "false";
            callback(result);
        }
    });
};

exports._grantMembershiprenewal = function(membership, member, callback) {
    let result = {};
    let refill_gems = parseInt(membership.gems);

    let updatedata = { $inc: { acct_gems: refill_gems } };
    updatedata.acct_membership = "sub";
    if (membership.validity == "1M") {
        updatedata.acct_membership_till = moment(membership.subs_date).add(1, "months");
    }

    if (membership.validity == "3M") {
        updatedata.acct_membership_till = moment(membership.subs_date).add(3, "months");
    }

    if (membership.validity == "6M") {
        updatedata.acct_membership_till = moment(membership.subs_date).add(6, "months");
    }

    if (membership.validity == "1Y") {
        updatedata.acct_membership_till = moment(membership.subs_date).add(1, "years");
    }

    DbModel.findOneAndUpdate({ _id: member }, updatedata, { new: true }, function(err, acc_details) {
        if (!err) {
            result.status = true;
            result.details = acc_details;
            callback(result);
        } else {
            result.status = "false";
            callback(result);
        }
    });
};

exports._fillGems = function(update_info, account, callback) {
    let updatedata;
    let result = {};
    let refill_gems = parseInt(update_info.gems);

    if (update_info.filltype == "credit") {
        updatedata = { $inc: { acct_gems: refill_gems } };
    }

    if (update_info.filltype == "debit") {
        updatedata = { $inc: { acct_gems: refill_gems * -1 } };
    }

    DbModel.findOneAndUpdate({ _id: account }, updatedata, { new: true }, function(err, acc_details) {
        if (!err) {
            result.status = "true";
            result.details = acc_details;
            callback(result);
        } else {
            result.status = "false";
            callback(result);
        }
    });
};

exports._sufficientGems = function(account_id, gems_available) {
    return new Promise(function(resolve, reject) {
        DbModel.findOne({ _id: account_id }, function(err, acc_details) {
            if (err) {
                reject(err);
            } else {
                let gems_count = parseInt(gems_available);
                let account_gems = parseInt(acc_details.acct_gems);
                if (gems_count <= account_gems) {
                    resolve(acc_details);
                } else {
                    let error = "user is out of gems";
                    reject(error);
                }
            }
        });
    });
};

exports._searchPartner = function(filters, callback) {
    let no_account_info = {};
    let SearchQuery = DbModel.find({
        acct_live: 2
    }).limit(1);
    SearchQuery.exec(function(err, account_info) {
        if (err || account_info == "") {
            callback(no_account_info);
        } else {
            callback(account_info);
        }
    });
};

exports._accountDetails = function(account_id, callback) {
    let no_account_info = {};
    DbModel.findOne(
    {
        _id: account_id
    },
    function(err, account_info) {
        if (err || account_info == "" || typeof account_info == "undefined") {
            callback(no_account_info);
        } else {
            callback(account_info);
        }
    }
    );
};

exports.activeStatus = function(req, res) {
    let checkQuery = DbModel.countDocuments({
        _id: req.params.userId,
        acct_status: 1
    });
    checkQuery.exec(function(err, iscount) {
        if (!err && iscount > 0) {
            deviceService.notifyLogin(req.params.userId);
            return res.json({
                status: "true",
                message: "active user"
            });
        } else {
            return res.json({
                status: "false",
                message: "inactive user"
            });
        }
    });
};

function _signupCredits(account_id) {
    let settingsQuery = settingModel.findOne({}).limit(1);
    settingsQuery.exec(function(err, settings) {
        if (!err) {
            let signup_gems = parseInt(settings.signup_credits);
            let updatedata = { $inc: { acct_gems: signup_gems } };
            DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(err, acc_details) {
                if (err) {
// console.log(err);
}
});
        }
    });
}

exports._filterDebits = function(account_id, callback) {
    let settingsQuery = settingModel.findOne({}).limit(1);
    let signup_gems;
    settingsQuery.exec(function(err, settings) {
        if (!err) {
            let query = DbModel.findOne({ _id: account_id }).limit(1);
            query.exec(function(err, accountdetails) {
                if (!err && accountdetails) {
                    signup_gems = parseInt(settings.gem_reduction.unsub);
                    if (accountdetails.acct_membership == "sub") {
                        signup_gems = parseInt(settings.gem_reduction.sub);
                    }
                    let updatedata = { $inc: { acct_gems: signup_gems * -1 } };
                    DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(
                        err,
                        acc_details
                        ) {
                        if (!err) {
                            callback(acc_details.acct_gems);
                        } else {
                            callback(0);
                        }
                    });
                } else {
                    callback(0);
                }
            });
        } else {
            callback(0);
        }
    });
};

/* convert gift to gems */
exports.GiftEarnings = function(req, res) {
    let withdraw_type = req.body.type;
    let updatedata;
    if (withdraw_type == "gems") {
        let query = DbModel.findById(req.body.user_id);
        query.exec(function(err, accountdetails) {
            if (!err) {
                let settingsQuery = settingModel.findOne({}).limit(1);
                settingsQuery.exec(function(err, settings) {
                    if (!err) {
                        let gems_per = settings.gems_commision_per / 100;
                        let sub_gems = Math.round(accountdetails.acct_gift_earnings * gems_per);
                        let gemspoints = Math.abs(accountdetails.acct_gift_earnings - sub_gems);
                        updatedata = {
                            acct_gift_earnings: 0,
                            acct_gifts: 0,
                            $inc: { acct_gems: gemspoints }
                        };
                        DbModel.findOneAndUpdate({ _id: req.body.user_id }, updatedata, { new: true }, function(
                            err,
                            acc_details
                            ) {
                            if (!err) {
                                return res.json({
                                    status: "true",
                                    user_id: acc_details._id,
                                    total_gems: acc_details.acct_gems
                                });
                            } else {
                                return res.json({
                                    status: "false",
                                    message: "Something went to be wrong"
                                });
                            }
                        });
                    } else {
                        return res.json({
                            status: "false",
                            message: "Something went to be wrong"
                        });
                    }
                });
            } else {
                return res.json({
                    status: "false",
                    message: "Something went to be wrong"
                });
            }
        });
    } else {
        return res.json({
            status: "true",
            message: "Payment done successfully."
        });
    }
};

exports.userInvites = function(req, res) {
    let settingsQuery = settingModel.findOne({}).limit(1);
    settingsQuery.exec(function(err, settings) {
        if (!err) {
            let referral_code = req.params.referalCode;
            let invite_gems = parseInt(settings.invite_credits);
            let updatedata = { $inc: { acct_gems: invite_gems } };
            DbModel.findOneAndUpdate({ acct_referral_code: referral_code }, updatedata, { new: true }, function(
                err,
                acc_details
                ) {
                if (err) {
                    return res.json({
                        status: "false",
                        message: "Something went to be wrong"
                    });
                } else {
                    return res.json({
                        status: "true",
                        message: "Invitation Credited successfully"
                    });
                }
            });
        } else {
            return res.json({
                status: "false",
                message: "Something went to be wrong"
            });
        }
    });
};

/* earn gems by watching video ads */
exports.EarnRewards = function(req, res) {
    let settingsQuery = settingModel.findOne({}).limit(1);
    settingsQuery.exec(function(err, settings) {
        if (!err) {
            let account_id = req.params.userId;
            let ads_gems = parseInt(settings.ads_credits);
            let updatedata = { $inc: { acct_gems: ads_gems } };
            DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(err, acc_details) {
                if (err) {
                    return res.json({
                        status: "false",
                        message: "Something went to be wrong"
                    });
                } else {
                    return res.json({
                        status: "true",
                        total_gems: acc_details.acct_gems,
                        message: "Invitation Credited successfully"
                    });
                }
            });
        } else {
            return res.json({
                status: "false",
                message: "Something went to be wrong"
            });
        }
    });
};

/* suspend the account @posting inappropriate videos */
exports.suspendAccount = function(account_id) {
    let tomorrow = moment(new Date())
    .add(1, "days")
    .toISOString();
    let updatedata = { acct_status: 0, acct_block_reset: tomorrow };
    DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(err, acc_details) {
        /* console.log("Account is  suspended"); */
        if (err) {
//console.log(err);
}
});
};

/* register the app platform where user signin last */
exports.registerPlatform = function(user_id, platform) {
    let updatedata = { acct_platform: platform };
    DbModel.findOneAndUpdate({ _id: user_id }, updatedata, { new: true }, function(err, acc_details) {
        /* console.log("Account Platform is updated"); */
        if (err) {
// console.log(err);
} else {
    console.log("success");
}
});
};

/* search all accounts */
exports.allAccounts = function(req, res) {
    if (!req.body.user_id) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let offset = 0;
        let limit = 20;
        let searchString = {};
        if (req.body.limit) {
            limit = parseInt(req.body.limit);
        }

        if (req.body.offset) {
            offset = parseInt(req.body.offset);
        }

        if (req.body.user_id) {
            searchString._id = { $nin: [ req.body.user_id ] };
        }

        if (req.body.search_key) {
            searchString.acct_name = { $regex: req.body.search_key, $options: "i" };
        }

        let SearchQuery = DbModel.find(searchString)
        .skip(offset)
        .limit(limit);
        SearchQuery.exec(function(err, account_info) {
            if (!err && account_info) {
                let allaccounts = [];
                account_info.forEach(function(accountInfo) {
                    allaccounts.push({
                        user_id: accountInfo._id,
                        name: accountInfo.acct_name,
                        user_image: config.IMAGE_BASE_URL + "accounts/" + accountInfo.acct_photo,
                        followers: accountInfo.acct_followers_count ? accountInfo.acct_followers_count : 0,
                        followings: accountInfo.acct_followings_count ? accountInfo.acct_followings_count : 0
                    });
                });
                return res.json({
                    status: "true",
                    result: allaccounts
                });
            } else {
                return res.json({
                    status: "false",
                    message: "Something went to be wrong"
                });
            }
        });
    }
};

/* search user with username */
exports.searchUser = function(req, res) {
    if (!req.body.user_id || !req.body.search_key) {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    } else {
        let filterStatus = { acct_status: 1 };
        let filterName = {};
        let filterChats = {
            _id: {
                $nin: [ req.body.user_id ]
            }};

            if (req.body.search_key) {
                filterName = { acct_username: req.body.search_key };
            }

            let SearchQuery = DbModel.findOne({
                $and: [
                filterStatus,
                filterChats,
                filterName
                ]
            });

            SearchQuery.exec(function(err, accountInfo) {
                if (!err && accountInfo) {
                    let birthdate = moment(accountInfo.acct_birthday).format("DD/MM/YYYY");
                    let matchedProfiles = [{
                        user_id: accountInfo._id,
                        name: accountInfo.acct_name,
                        user_image: accountInfo.acct_username,
                        age: accountInfo.acct_age,
                        dob: birthdate,
                        gender: accountInfo.acct_gender,
                        user_image: accountInfo.acct_photo,
                        premium_member: accountInfo.acct_membership == "sub" ? "true" : "false",
                        location: accountInfo.acct_location,
                        privacy_age: accountInfo.acct_show_age,
                        privacy_contactme: accountInfo.acct_show_contactme,
                    }];

                    return res.json({
                        status: "true",
                        users_list: matchedProfiles
                    });

                } else {
                    return res.json({
                        status: "false",
                        message: "No user found."
                    });
                }
            });
        }
    };



    /* charge user for making calls */
    exports.callDebits = function(req, res) {
        if (!req.params.userId) {
            return res.json({
                status: "false",
                message: "Something went to be wrong"
            });
        } else {
            let account_id = req.params.userId;
            let settingsQuery = settingModel.findOne({}).limit(1);
            settingsQuery.exec(function(err, settings) {
                if (!err) {
                    let reduce_gems = parseInt(settings.calls_debits);
                    let updatedata = { $inc: { acct_gems: reduce_gems * -1 } };
                    DbModel.findOneAndUpdate({ _id: account_id }, updatedata, { new: true }, function(err, acc_details) {
                        if (!err) {
                            return res.json({
                                status: "true",
                                message: "Gems debited successfully"
                            });
                        } else {
                            /* console.log(err); */
                        }
                    });
                }
            });
        }
    };

    exports.profileLikes = function(followed_account, follower_account) {
        let newfollowers = { $inc: { acct_followers_count: 1 } };

        let newfollowings = { $inc: { acct_followings_count: 1 } };

        DbModel.findOneAndUpdate({ _id: followed_account }, newfollowers, { new: true }, function(err, acc_details) {
            if (err) {
                console.log(err);
            }
        });

        DbModel.findOneAndUpdate({ _id: follower_account }, newfollowings, { new: true }, function(err, acc_details) {
            if (err) {
                console.log(err);
            }
        });
    };

    exports.profiledisLikes = function(followed_account, follower_account) {
        let newfollowers = { $inc: { acct_followers_count: -1 } };

        let newfollowings = { $inc: { acct_followings_count: -1 } };

        DbModel.findOneAndUpdate({ _id: followed_account }, newfollowers, { new: true }, function(err, acc_details) {
            if (err) {
                console.log(err);
            }
        });

        DbModel.findOneAndUpdate({ _id: follower_account }, newfollowings, { new: true }, function(err, acc_details) {
            if (err) {
                console.log(err);
            }
        });
    };

    exports.profileStreams = function(publisher_id) {
        let newstreams = { $inc: { acct_streams: 1 } };
        DbModel.findOneAndUpdate({ _id: publisher_id }, newstreams, { new: true }, function(err, acc_details) {
            if (err) {
                /* console.log(err); */
            }
        });
    };


    exports.delStreams = function(publisher_id) {
        let newstreamss = { $inc: { acct_streams: -1 } };
        DbModel.findOneAndUpdate({ _id: publisher_id }, newstreamss, { new: true }, function(err, acc_details) {
            if (!err) {
                /*console.log("deleted"); */
            }
        });
    };

    exports.addWatchCount = function(publisher_id,action) {
        let newstreams = { $inc: { acct_watched_count: 1 } };
        if(action === "delete" ){
            newstreams = { acct_watched_count: 0 };
        }
        DbModel.findOneAndUpdate({ _id: publisher_id }, newstreams, { new: true }, function(err, acc_details) {
            if (!err) {
               console.log("Watch count is updating"); 
           }
       });
    };

    