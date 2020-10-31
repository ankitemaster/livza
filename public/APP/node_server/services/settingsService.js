const async = require("async");
const config = require("../config/keys");

let settingModel = require("../models/settingModel");
let giftModel = require("../models/giftModel");
let subscriptionModel = require("../models/subscriptionModel");
let chatMsgs = require("../models/adminchatModel");
let moment = require("moment");

exports.AppSettings = function(req, res) {
    if (req.params.platform) {
        let settingsQuery = settingModel.findOne({}).limit(1);
        let giftsQuery = giftModel.find({});
        let subsQuery = subscriptionModel.find({ platform: req.params.platform });
        let tasks = [
            function init_settings(cb) {
                settingsQuery.exec(function(err, settings) {
                    if (!err) {
                        if(isEmpty(settings.adskey)){
                            settings.adskey = "";
                        }
                        if(isEmpty(settings.videoadskey)){
                            settings.videoadskey = "";
                        }
                        let appdefault_settings = {
                            status: "true",
                            freegems: settings.signup_credits,
                            video_calls: settings.calls_debits,
                            reports: settings.report_titles,
                            prime_details: settings.prime_desc,
                            gifts_details: settings.gifts_desc,
                            filter_gems: settings.gem_reduction,
                            filter_options: settings.filter_option,
                            locations: settings.locations.split(","),
                            invite_credits: settings.invite_credits,
                            google_ads_client: settings.adskey,
                            video_ads_client: settings.videoadskey,
                            show_ads: settings.adsenable,
                            video_ads: settings.video_ads,
                            schedule_video_ads: settings.schedule_video_ads,
                            welcome_message: settings.welcome_message,
                            contact_email: settings.contact_emailid,
                            show_money_conversion: 0,
                            stream_connection_info: config.ANTMEDIA
                        };
                        return cb(null, appdefault_settings);
                    } else {
                        return cb(err);
                    }
                });
            },
            function gift_settings(appdefault_settings, cb) {
                giftsQuery.exec(function(err, giftpacks) {
                    if (!err) {
                        let result = appdefault_settings;
                        result.gifts = giftpacks;
                        return cb(null, result);
                    } else {
                        return cb(err);
                    }
                });
            },
            function membership_settings(allsettings, cb) {
                subsQuery.exec(function(err, membershippacks) {
                    if (!err) {
                        let result = allsettings;
                        result.membership_packages = membershippacks;
                        return cb(null, result);
                    } else {
                        return cb(err);
                    }
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
    } else {
        return res.json({
            status: "false",
            message: "Something went to be wrong"
        });
    }
};

exports.AdminMessages = function(req, res) {
  if (req.params.userId && req.params.updateFrom && req.params.timeStamp && req.params.platform ) {
    let last_msg_received = parseInt(req.params.timeStamp);
    let account_joined_at = new Date(req.params.updateFrom);
    let chatsQuery = chatMsgs.aggregate(
      [
        {
          $project: {
            _id: 0,
            msg_id: "$_id",
            msg_from: "$msg_from",
            msg_type: "$msg_type",
            msg_data: "$msg_data",
            msg_at: "$msg_at",
            msg_platform: "$msg_platform",
            msg_to: "$msg_to",
            created_at: "$created_at"
          }
        },

        {
          $match: {
            $and: [
              { created_at: { $gte: account_joined_at } },
              { msg_at: { $gt: last_msg_received } },
              { msg_platform: req.params.platform },
              { msg_to: { $in: ["all", req.params.userId] } }
            ]
          }
        }
      ],
      function(err, chatmessages) {
        if (err) {
          return res.json({
            status: "false",
            message: "Something went to be wrong"
          });
        } else {
          if (isEmpty(chatmessages)) {
            return res.json({
              status: "false",
              message: "No data found."
            });
          } else {
            return res.json({
              status: "true",
              message_data: chatmessages
            });
          }
        }
      }
    );
  } else {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  }
};

/* check the object is empty (or) not */
function isEmpty(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key)) return false;
    }
    return true;
}
