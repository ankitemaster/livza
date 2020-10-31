let DbModel = require("../models/subscriptionModel");

exports._validMembership = function(membership_id, callback) {
    let searchBy = { subs_title: membership_id };
    DbModel.countDocuments(searchBy, function(err, validity) {
        if (validity > 0 && !err) {
            callback("success");
        } else {
            callback("failure");
        }
    });
};
