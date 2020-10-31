let express = require("express");
let router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let service = require("../services/accountService");
let uploadService = require("../services/uploadService");

/* routes */
router.get("/isactive/:userId", service.activeStatus);
router.post("/signin", service.accountLogin);
router.post("/signup", service.accountSignup);
router.post("/profile", authentication, service.accountProfile);
router.post("/uploadprofile", authentication, uploadService.accountProfileImage);
router.post("/upmychat", authentication, uploadService.accountChats);
router.post("/upmyvideo", authentication, uploadService.randomChatImage);
router.get("/invitecredits/:referalCode", authentication, service.userInvites);
router.get("/rewardvideo/:userId", authentication, service.EarnRewards);
router.get("/chargecalls/:userId", authentication, service.callDebits);
router.post("/showall", authentication, service.allAccounts);
router.post("/searchuser", authentication, service.searchUser);

module.exports = router;
