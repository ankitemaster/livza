let config = require("../config/keys");
let moment = require("moment");
let mongoose = require("mongoose");

/* MODELS */
let videoChats = require("../models/videochatModel");
let randomUsers = require("../models/accountModel");
let blockUsers = require("../models/blockModel");

/* services */
let followingService = require("../services/followingService");

exports.webrtcParams = function(req, res) {
  if (req.params.userId) {
    res.json({
      status: "true",
      params: config.RTCPARAMS
    });
  } else {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  }
};

exports._doChat = function(user, partner) {
  videoChats.countDocuments(
    {
      user_id: user,
      partner_id: partner
    },
    function(err, count) {
      if (count > 0) {
        let updatedata = { chat_recent_on: moment.utc().format() };
        videoChats.findOneAndUpdate(
          { user_id: user, partner_id: partner },
          updatedata,
          { new: true },
          function(err, acc_details) {
            if (err) {
              // console.log(err);
            }
          }
        );
      } else {
        let newChats = new videoChats({
          user_id: user,
          partner_id: partner,
          chat_recent_on: moment.utc().format()
        });
        newChats.save(function(err) {
          if (err) {
            // console.log(err);
          }
        });
      }
    }
  );
};

exports.RecentHistory = function(req, res) {
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

    let query = videoChats
      .countDocuments({
        user_id: req.params.userId
      })
      .skip(offset)
      .limit(limit);

    query.exec(function(err, count) {
      if (count > 0) {
        videoChats
          .find({
            user_id: req.params.userId
          })
          .populate("partner_id")
          .limit(limit)
          .skip(offset)
          .sort({
            chat_recent_on: -1
          })
          .exec(function(err, userlist) {
            if (!err) {
              let partners_list = [];
              userlist.forEach(function(userinfo) {
                let birthdate = moment(
                  userinfo.partner_id.acct_birthday
                ).format("DD/MM/YYYY");
                partners_list.push({
                  user_id: userinfo.partner_id._id,
                  user_image: userinfo.partner_id.acct_photo,
                  name: userinfo.partner_id.acct_name,
                  age: userinfo.partner_id.acct_age,
                  dob: birthdate,
                  gender: userinfo.partner_id.acct_gender,
                  premium_member:
                    userinfo.partner_id.acct_membership == "sub"
                      ? "true"
                      : "false",
                  location: userinfo.partner_id.acct_location,
                  login_id:
                    userinfo.partner_id.acct_sync == "phonenumber"
                      ? userinfo.partner_id.acct_phoneno
                      : userinfo.partner_id.acct_facebookid,
                  privacy_age: userinfo.partner_id.acct_show_age,
                  privacy_contactme: userinfo.partner_id.acct_show_contactme,
                  show_notification: userinfo.partner_id.acct_alert,
                  chat_notification: userinfo.partner_id.acct_chat_alert,
                  follow_notification: userinfo.partner_id.acct_follow_alert,
                  referal_link: "",
                  follow: "false",
                  last_chat: userinfo.chat_recent_on
                });
              });
              return res.json({
                status: "true",
                users_list: partners_list
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
          message: "No records found"
        });
      }
    });
  }
};

exports.RandomPartner = function(userID, recentchats, filters, callback) {
  /* exports.RandomPartner = function(req, res) { */
  let isMembership = 0;
  /* let recentchats = JSON.parse(req.body.recentchats);
  let filters = JSON.parse(req.body.filters);
  let user_account = req.body.user_id; */
  /* let recentchats = JSON.parse(recentchats);
  let filters = JSON.parse(filters); */
  let user_account = userID;
  let filterstatusBy = { acct_status: 1, _id: user_account };
  let filtermemberBy = { acct_membership: "sub", _id: user_account };
  let location_param = 0;
  let gender_param = 0;
  let age_param = 0;

  let membershipQuery = randomUsers.countDocuments(filtermemberBy).limit(1);
  let statusQuery = randomUsers.countDocuments(filterstatusBy).limit(1);
  let searchQuery = {};
  let blockResult = { status: "blocked" };
  let errResult = { status: "failure" };
  statusQuery.exec(function(err, statusCount) {
    if (statusCount > 0) {
      membershipQuery.exec(function(err, membershipCount) {
        if (!err) {
          if (membershipCount > 0) {
            isMembership = 1;
          }

          /* filter by */
          let filterStatus = { acct_live: "2" };
          let filterChats = {
            _id: {
              $nin: recentchats
            }
          };
          let filterCountries = {};
          let filterGender = {};
          let filterAge = {};

          if (filters.gender && filters.gender != "both") {
            filterGender = { acct_gender: filters.gender };
            gender_param = 1;
          }

          if (filters.location) {
            if (filters.location.length > 0) {
              filterCountries = {
                acct_location: {
                  $in: filters.location
                }
              };
            } else {
              filterCountries = {
                acct_location: {
                  $nin: filters.location
                }
              };
            }
            location_param = 1;
          }

          if (filters.min_age && filters.max_age) {
            let search_from = parseInt(filters.min_age);
            let search_to = parseInt(filters.max_age);
            filterAge = { acct_age: { $gte: search_from, $lte: search_to } };
            age_param = 1;
          }

          /* filters */
          /* console.log(filterStatus);
          console.log(isMembership);
          console.log(filterChats);
          console.log(filterGender);
          console.log(filterCountries);
          console.log(filterAge); */

          if (isMembership == 0) {
            let filterMembership = { acct_membership: "unsub" };
            searchQuery = randomUsers
              .findOne({
                $and: [
                  filterStatus,
                  filterChats,
                  filterMembership,
                  filterGender,
                  filterCountries,
                  filterAge
                ]
              })
              .limit(1);
          } else {
            searchQuery = randomUsers
              .findOne({
                $and: [
                  filterStatus,
                  filterChats,
                  filterGender,
                  filterCountries,
                  filterAge
                ]
              })
              .limit(1);
          }

          searchQuery.exec(function(err, accountdetails) {
            if (accountdetails && !err) {
              let result = {};
              result.status = "success";
              result.partner_id = accountdetails._id;
              result.filter_search_result = "false";

              if (location_param == 1) {
                if (
                  filters.location.indexOf(accountdetails.acct_location) > -1
                ) {
                  result.filter_search_result = "true";
                }
              }

              if (gender_param == 1) {
                if (filters.gender === accountdetails.acct_gender) {
                  result.filter_search_result = "true";
                }
              }

              if (age_param == 1) {
                if (
                  filters.min_age <= accountdetails.acct_age &&
                  filters.max_age >= accountdetails.acct_age
                ) {
                  result.filter_search_result = "true";
                }
              }

              callback(result);
            } else {
              callback(errResult);
            }
          });
        } else {
          callback(errResult);
        }
      });
    } else {
      callback(blockResult);
    }
  });
};

exports.CheckPartner = function(userID, callback) {
  let partner_id = userID;

  let searchBy = {
    acct_live: 2,
    _id: partner_id
  };

  let searchCountQuery = randomUsers.countDocuments(searchBy);
  let searchQuery = randomUsers
    .findOne(searchBy)
    .sort({ _id: -1 })
    .limit(1);

  searchCountQuery.exec(function(err, randomcount) {
    if (randomcount > 0) {
      searchQuery.exec(function(err, accountdetails) {
        if (accountdetails && !err) {
          let partner_id = accountdetails._id;
          callback(partner_id);
        } else {
          callback(0);
        }
      });
    } else {
      callback(0);
    }
  });
};

exports._isBlocked = function(blockedby, blocked, callback) {
  let searchBy = { user_id: blockedby, blocked_user: blocked };
  blockUsers.countDocuments(searchBy, function(err, validity) {
    if (validity > 0 && !err) {
      callback(0);
    } else {
      callback(1);
    }
  });
};

exports.Clearchats = function(req, res) {
  if (!req.params.userId) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    videoChats.countDocuments(
      {
        user_id: req.params.userId
      },
      function(err, count) {
        if (count > 0) {
          videoChats.remove(
            {
              user_id: req.params.userId
            },
            function(err, count) {
              return res.json({
                status: "true",
                message: "Chats cleared successfully"
              });
            }
          );
        } else {
          return res.json({
            status: "false",
            message: "No Messages found"
          });
        }
      }
    );
  }
};
