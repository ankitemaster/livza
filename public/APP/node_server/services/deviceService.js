let DbModel = require("../models/deviceModel");
let accountService = require("../services/accountService");
let moment = require("moment");

exports.DeviceRegister = function(req, res) {
  if (
    !req.body.user_id ||
    !req.body.device_type ||
    !req.body.device_id ||
    !req.body.device_model
  ) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    let deviceType = req.body.device_type;
    let devicePlatform;
    if (deviceType == "0") {
      devicePlatform = "ios";
    }else{
      devicePlatform = "android";
    }
    
    DbModel.countDocuments(
      {
        user_id: req.body.user_id
      },
      function(err, count) {
        if (count > 0) {
          DbModel.findOneAndUpdate(
            {
              user_id: req.body.user_id
            },
            {
              $set: req.body
            }
          ).exec(function(err) {
            if (err) {
              /* console.log(err); */
              return res.json({
                status: "false",
                message: "Something went to be wrong"
              });
            } else {
              accountService.registerPlatform(req.body.user_id, devicePlatform);
              return res.json({
                status: "true",
                message: "Registered successfully"
              });
            }
          });
        } else {
          req.body.notified_at = moment().toISOString();
          let newDevices = new DbModel(req.body);
          newDevices.save(function(err) {
            if (!err) {
              accountService.registerPlatform(req.body.user_id, devicePlatform);
              return res.json({
                status: "true",
                message: "Registered successfully"
              });
            } else {
              /* console.log(err); */
              return res.json({
                status: "false",
                message: "Something went to be wrong"
              });
            }
          });
        }
      }
    );
  }
};

exports.DeviceUnregister = function(req, res) {
  if (!req.params.deviceId) {
    return res.json({
      status: "false",
      message: "Something went to be wrong"
    });
  } else {
    DbModel.countDocuments(
      {
        device_id: req.params.deviceId
      },
      function(err, count) {
        if (count > 0) {
          DbModel.findOneAndRemove(
            {
              device_id: req.params.deviceId
            },
            function(err, count) {
              return res.json({
                status: "true",
                message: "Unregistered successfully"
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

exports.notifyLogin = function(account) {
  let notifyQuery = DbModel.findOneAndUpdate(
    {
      user_id: account
    },
    {
      notified_at: moment().toISOString()
    }
  ).sort({
    _id: -1
  });

  notifyQuery.exec(function(err) {
    if (err) {
      // console.log(err);
    }
  });
};
