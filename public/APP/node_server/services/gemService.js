/* models */
let DbModel = require("../models/gemModel");

exports.GemsPackages = function(req, res) {
  if (
    !req.params.userId ||
    !req.params.limit ||
    !req.params.offset ||
    !req.params.platform
  ) {
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

    let query = DbModel.countDocuments({ platform: req.params.platform })
      .skip(offset)
      .limit(limit);

    query.exec(function(err, gemscount) {
      console.log("GEms Count" + gemscount);
      if (gemscount > 0) {
        DbModel.find({ platform: req.params.platform })
          .select({
            _id: 0,
            gem_title: 1,
            gem_icon: 1,
            gem_price: 1,
            gem_count: 1,
            platform: 1
          })
          .limit(limit)
          .skip(offset)
          .exec(function(err, gems_packages) {
            if (!err) {
              return res.json({
                status: "true",
                gems_list: gems_packages
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
          message: "No records1 found"
        });
      }
    });
  }
};

exports._isgemsVaild = function(gid, callback) {
  let searchBy = { gem_title: gid };
  DbModel.countDocuments(searchBy, function(err, validity) {
    if (validity > 0 && !err) {
      callback("success");
    } else {
      callback("failure");
    }
  });
};
