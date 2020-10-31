const express = require("express");
const router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let follow_activity = require("../services/followingService");
let report_activity = require("../services/reportService");
let default_activity = require("../services/settingsService");
let gem_activity = require("../services/gemService");
let upload_activity = require("../services/uploadService");
let account_activity = require("../services/accountService");
let block_activity = require("../services/blockService");
let hashtag_activity = require("../services/hashtagService");
let stream_activity = require("../services/streamService");
let watch_history_activity = require("../services/watchhistoryService");
let trending_activity = require("../services/trendingService");

/* follow activity routes */
router.post("/follow", authentication, follow_activity.FollowPeople);
router.get("/followerslist/:userId/:offset/:limit",authentication,follow_activity.AccountFollowers);
router.get("/followingslist/:userId/:offset/:limit",authentication,follow_activity.AccountFollowings);
router.post("/mutualfollowers",authentication,follow_activity.MutualFollowers);

/* report users */
router.post("/reportuser", authentication, report_activity.ReportPeople);
router.post("/uploadreport", authentication, upload_activity.ReportImage);

/* block users */
router.post("/blockuser", authentication, block_activity.blockUser);
router.get("/blockeduserslist/:userId/:offset/:limit", authentication, block_activity.listBlockedUsers);

/* app defaults */
router.get("/adminmessages/:userId/:platform/:updateFrom/:timeStamp",default_activity.AdminMessages);

/* admin messsages */
router.get("/appdefaults/:platform", default_activity.AppSettings);

router.get("/gemstore/:userId/:platform/:offset/:limit",authentication,gem_activity.GemsPackages);
router.post("/gifttogems", authentication, account_activity.GiftEarnings);

/* alert users */
router.get("/alerts", authentication, default_activity.AppSettings);

/* hashtags */
router.post("/hashtags", authentication, hashtag_activity.listAllHashtags);
router.post("/explorestreams", authentication, hashtag_activity.exploreStreams);

/* watch history */
router.get("/watchhistory/:userId/:offset/:limit", authentication, watch_history_activity.listWatchHistory);
router.post("/updatewatchcount", authentication, watch_history_activity.updateWatchCount);
router.delete("/clearwatchhistory/:userId",authentication,watch_history_activity.clearWatchHistory);

/* trending hahstags */
router.get("/trendinghashtags", authentication, trending_activity.trendingHashtags);
router.get("/popularcountries/:filterBy", authentication, stream_activity.popularCountries);

module.exports = router;
