const express = require("express");
const router = express.Router();
const authentication = require("../middleware/authMiddleware");

/* services */
const service = require("../services/streamService");

/* routes */
router.post("/", authentication, service.liveStreams);
router.get("/createstream/:publisher_id", authentication, service.createStream);
router.post("/startstream", authentication, service.startStream);
router.post("/stopstream", authentication, service.stopStream);
router.post("/streamdetails", service.streamDetails);
router.post("/uploadstream", authentication, service.uploadStream);
router.post("/reportstream", authentication, service.reportStream);
router.post("/sharestream", authentication, service.shareStream);
router.delete("/deletestream/:publisher_id/:name", authentication, service.deleteStream);

module.exports = router;
