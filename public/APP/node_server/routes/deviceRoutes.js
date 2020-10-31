const express = require("express");
const router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let service = require("../services/deviceService");

/* routes */
router.post("/register", service.DeviceRegister);
router.delete(
  "/unregister/:deviceId",
  authentication,
  service.DeviceUnregister
);

module.exports = router;
