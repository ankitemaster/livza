let moment = require("moment");

let dbModel = require("../models/paymentModel");

/* services */
let accounts = require("../services/accountService");
let gems = require("../services/gemService");
let subscriptions = require("../services/subscriptionService");

/* models */
let accountsModel = require("../models/accountModel");
let subscriptionModel = require("../models/subscriptionModel");
let gemModel = require("../models/gemModel");

exports.PayMembership = function(req, res) {
  if (
    !req.body.user_id ||
    !req.body.membership_id ||
    !req.body.transaction_id ||
    !req.body.paid_amount
  ) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    subscriptions._validMembership(req.body.membership_id, function(result) {
      if (result == "success") {
        let payObject = {
          user_id: req.body.user_id,
          pymt_type: "membership",
          pymt_amt: req.body.paid_amount,
          pymt_transid: req.body.transaction_id
        };
        let newPayment = new dbModel(payObject);
        newPayment.save(function(err) {
          if (!err) {
            subscriptionModel.findOne(
              { subs_title: req.body.membership_id },
              function(err, subscriptionData) {
                if (!err) {
                  let updateData = {};
                  updateData.validity = subscriptionData.subs_validity;
                  updateData.gems = subscriptionData.subs_gems;
                  let updateTo = req.body.user_id;
                  accounts._grantMembership(updateData, updateTo, function(
                    result
                  ) {
                    if (result.status) {
                      let account_info = result.details;
                      successfulPurchase("membership", req.body.membership_id);
                      return res.json({
                        status: "true",
                        user_id: account_info._id,
                        premium_member:
                          account_info.acct_membership == "sub"
                            ? "true"
                            : "false",
                        premium_expiry_date: moment(
                          account_info.acct_membership_till
                        ).format("DD/MM/YYYY"),
                        available_gems: account_info.acct_gems
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
              }
            );
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
  }
};

exports.buyGems = function(req, res) {
  if (
    !req.body.user_id ||
    !req.body.gem_id ||
    !req.body.paid_amount ||
    !req.body.transaction_id
  ) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    gems._isgemsVaild(req.body.gem_id, function(result) {
      if (result == "success") {
        let payObject = {
          user_id: req.body.user_id,
          pymt_type: "gems",
          pymt_amt: req.body.paid_amount,
          pymt_transid: req.body.transaction_id
        };
        let newPayment = new dbModel(payObject);
        newPayment.save(function(err) {
          if (!err) {
            gemModel.findOne({ gem_title: req.body.gem_id }, function(
              err,
              packagesData
            ) {
              if (!err) {
                let updateData = {};
                let updateTo = req.body.user_id;
                updateData.filltype = "credit";
                updateData.gems = packagesData.gem_count;
                accounts._fillGems(updateData, updateTo, function(result) {
                  if (result.status) {
                    let account_info = result.details;
                    successfulPurchase("gems", req.body.gem_id);
                    return res.json({
                      status: "true",
                      user_id: account_info._id,
                      available_gems: account_info.acct_gems
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
          status: "false",
          message: "Something went to be wrong"
        });
      }
    });
  }
};

function successfulPurchase(type, name) {
  let updatedata = {
    $inc: { purchase_count: 1 }
  };
  if (type == "membership") {
    subscriptionModel.findOneAndUpdate(
      { subs_title: name },
      updatedata,
      { new: true },
      function(err, update_details) {
        if (err) {
          /* console.log(err); */
        }
      }
    );
  } else {
    gemModel.findOneAndUpdate(
      { gem_title: name },
      updatedata,
      { new: true },
      function(err, update_details) {
        if (err) {
          /*  console.log(err); */
        }
      }
    );
  }
}

exports.AutoRenewal = function(req, res) {
  if (!req.body.user_id || !req.body.package_id || !req.body.renewal_time) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    let search_renewal = {
      _id: req.body.user_id,
      acct_membership: "unsub"
    };
    subscriptionModel.findOne({ subs_title: req.body.package_id }, function(
      err,
      subscriptionData
    ) {
      if (!err) {
        accountsModel.countDocuments(search_renewal, function(err, isRenewal) {
          if (!err && isRenewal > 0) {
            let payObject = {
              user_id: req.body.user_id,
              pymt_type: "membership",
              pymt_amt: subscriptionData.subs_price
            };
            let newPayment = new dbModel(payObject);
            newPayment.save(function(err) {
              if (!err) {
                let updateTo = req.body.user_id;
                let updateData = {};
                updateData.validity = subscriptionData.subs_validity;
                updateData.subs_date = req.body.renewal_time;
                updateData.gems = subscriptionData.subs_gems;
                accounts._grantMembershiprenewal(updateData, updateTo, function(
                  result
                ) {
                  if (result.status) {
                    successfulPurchase("membership", req.body.membership_id);
                    return res.json({
                      status: "true",
                      message: "Auto Renewal done Successfully"
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
          status: "false",
          message: "Something went to be wrong"
        });
      }
    });
  }
};
