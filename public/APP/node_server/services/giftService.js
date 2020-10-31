let DbModel = require("../models/giftModel");

exports._validGift = function(gift_id, gems_count) {
    let giftsQuery = DbModel.find({ _id: gift_id, gft_gems: gems_count }).limit(1);
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
