const path = require("path");
const multer = require("multer");
const config = require("../config/keys");
const sightengine = require("sightengine")(
  config.SIGHTENGINE._userid,
  config.SIGHTENGINE._secretkey
);
const fs = require("fs");
/* services */
let accounts = require("../services/accountService");
let reports = require("../services/reportService");

let accountAssets = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, path.join(__dirname, config.ASSETS + "/accounts/"));
  },
  filename: (req, file, cb) => {
    cb(null, file.fieldname + "-" + Date.now());
  }
});

exports.accountProfileImage = function(req, res) {
  let user_storage = multer({
    storage: accountAssets
  }).single("profile_image");

  user_storage(req, res, function(err) {
    if (!err) {
      let user = req.body.user_id;
      let profile_pic = res.req.file.filename;
      if (config.SIGHTENGINE._mode == "off") {
        accounts._updateProfile(user, profile_pic);
        return res.json({
          status: "true",
          user_image: res.req.file.filename,
          message: "Profile Image uploaded successfully"
        });
      } else {
        let image_url = config.IMAGE_BASE_URL + "/accounts/" + profile_pic;
        let image_path = path.join(
          __dirname,
          config.ASSETS + "/accounts/" + profile_pic
        );
        sightengine
          .check(["nudity"])
          .set_url(image_url)
          .then(function(result) {
            if (result.status == "success") {
              let moderate_image = result.nudity;
              if (
                moderate_image.raw >=
                Math.max(moderate_image.partial, moderate_image.safe)
              ) {
                unlinkFile(image_path); // delete the image on moderation fails
                return res.json({
                  status: "rejected",
                  message: "Your image was rejected by our moderator."
                });
              } else {
                accounts._updateProfile(user, profile_pic);
                return res.json({
                  status: "true",
                  user_image: res.req.file.filename,
                  message: "Profile Image uploaded successfully"
                });
              }
            } else {
              return res.json({
                status: "false",
                message: "Something went to be wrong"
              });
            }
          })
          .catch(function(err) {
            return res.json({
              status: "false",
              message: "Something went to be wrong"
            });
          });
      }
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};

let reportAssets = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, path.join(__dirname, config.ASSETS + "/reports/"));
  },
  filename: (req, file, cb) => {
    cb(null, file.fieldname + "-" + Date.now());
  }
});

exports.ReportImage = function(req, res) {
  let report_storage = multer({
    storage: reportAssets
  }).single("report_image");

  report_storage(req, res, function(err) {
    if (!err) {
      let user = req.body.report_id;
      let report_img = res.req.file.filename;
      reports.UpdateReport(user, report_img);
      return res.json({
        status: "true",
        user_image: res.req.file.filename,
        message: "Report uploaded successfully"
      });
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};

let chatAssets = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, path.join(__dirname, config.ASSETS + "/chats/"));
  },
  filename: (req, file, cb) => {
    cb(null, file.fieldname + "-" + Date.now());
  }
});

exports.accountChats = function(req, res) {
  let chat_storage = multer({
    storage: chatAssets
  }).single("chat_image");

  chat_storage(req, res, function(err) {
    if (!err) {
      return res.json({
        status: "true",
        user_image: res.req.file.filename,
        message: "Image Uploaded successfully"
      });
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};

/* random chats */
let videoAssets = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, path.join(__dirname, config.ASSETS + "/randomchats/"));
  },
  filename: (req, file, cb) => {
    cb(null, file.fieldname + "-" + Date.now());
  }
});

exports.randomChatImage = function(req, res) {
  let random_chat_storage = multer({
    storage: videoAssets
  }).single("video_chat_image");

  random_chat_storage(req, res, function(err) {
    if (!err) {
      let user = req.body.user_id;
      let partner = req.body.partner_id;
      let chat_pic = res.req.file.filename;
      if (config.SIGHTENGINE._mode == "off") {
        accounts._updateChatPic(partner, chat_pic);
        return res.json({
          status: "true",
          user_image: res.req.file.filename,
          message: "Image uploaded successfully"
        });
      } else {
        let image_url = config.IMAGE_BASE_URL + "/randomchats/" + chat_pic;
        let image_path = path.join(
          __dirname,
          config.ASSETS + "/randomchats/" + chat_pic
        );
        sightengine
          .check(["nudity"])
          .set_url(image_url)
          .then(function(result) {
            if (result.status == "success") {
              let moderate_image = result.nudity;
              if (
                moderate_image.raw >=
                Math.max(moderate_image.partial, moderate_image.safe)
              ) {
                unlinkFile(image_path); // delete the image on moderation fails
                /* console.log("User" + partner + "is suspending now"); */
                accounts.suspendAccount(partner);
                return res.json({
                  status: "rejected",
                  message: "Your image was rejected by our moderator."
                });
              } else {
                accounts._updateChatPic(partner, chat_pic);
                return res.json({
                  status: "true",
                  user_image: res.req.file.filename,
                  message: "Image uploaded successfully"
                });
              }
            } else {
              return res.json({
                status: "false",
                message: "Something went to be wrong"
              });
            }
          })
          .catch(function(err) {
            return res.json({
              status: "false",
              message: "Something went to be wrong"
            });
          });
      }
    } else {
      return res.json({
        status: "false",
        message: "Something went to be wrong"
      });
    }
  });
};

/* DELETE FILE */
unlinkFile = function(imagepath) {
  fs.unlink(imagepath, function(err) {
    if (!err) {
      /* console.log("File deleted!"); */
    } else {
      /* console.log(err);
      console.log("File not deleted!"); */
    }
  });
};
