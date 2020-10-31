const express = require("express");
const router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let service = require("../services/chatService");

/* routes */
router.get("/rtcparams/:userId", authentication, service.webrtcParams);
router.get(
  "/recentvideochats/:userId/:offset/:limit",
  authentication,
  service.RecentHistory
);
router.get("/clearvideochats/:userId", authentication, service.Clearchats);
module.exports = router;
