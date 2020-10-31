const cron = require("node-cron");
const moment = require("moment");

/* services */
let notificationService = require("./notificationService");

/* models */
let accountModel = require("../models/accountModel");

/* expire subscription */
cron.schedule("* * * * *", () => {
  let searchfilter = {};
  let messageData = {};
  let expirySubscribers = [];
  let today = moment();
  messageData.scope = "subscriptionexpiry";
  messageData.message = "You Subscription has expired. Subscribe Now !";
  searchfilter.acct_membership = "sub";
  searchfilter.acct_membership_till = {
    $lte: today.toISOString()
  };
  accountModel.countDocuments(searchfilter, function(err, subscribersCount) {
    if (subscribersCount > 0) {
      accountModel.find(searchfilter, function(err, subscribers) {
        subscribers.forEach(function(sub) {
          let sub_user = sub._id;
          let updatedata = { acct_membership: "unsub" };
          expirySubscribers.push(sub._id);
          accountModel.findOneAndUpdate(
            { _id: sub_user },
            updatedata,
            { new: true },
            function(err, acc_details) {
              if (!err) {
              }
            }
          );
        });
        notificationService.sendNotifications(expirySubscribers, messageData);
      });
    } else {
      /* console.log("No Subscriptions expired today"); */
    }
  });
});

/* Auto Enable disabled accounts */
cron.schedule("* * * * *", () => {
  let searchfilter = {};
  let messageData = {};
  let notifyUsers = [];
  let todaystarts = moment().startOf("day");
  let todayends = moment().endOf("day");
  messageData.scope = "accountunblocked";
  messageData.message = "Your Account is unblocked from now";
  searchfilter.acct_status = 0;
  searchfilter.acct_block_reset = {
    $gte: todaystarts.toISOString(),
    $lte: todayends.toISOString()
  };
  accountModel.countDocuments(searchfilter, function(err, usersCount) {
    if (usersCount > 0) {
      accountModel.find(searchfilter, function(err, users) {
        users.forEach(function(sub) {
          let sub_user = sub._id;
          let updatedata = { acct_status: 1 };
          notifyUsers.push(sub._id);
          accountModel.findOneAndUpdate(
            { _id: sub_user },
            updatedata,
            { new: true },
            function(err, acc_details) {
              if (!err) {
              }
            }
          );
        });
        notificationService.sendNotifications(notifyUsers, messageData);
      });
    } else {
      /* console.log("No accounts disabled for now"); */
    }
  });
});
