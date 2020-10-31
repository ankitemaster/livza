const express = require("express");
const router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let paymentService = require("../services/paymentService");

/* routes */
router.post("/subscription", authentication, paymentService.PayMembership);
router.post("/purchasegems", authentication, paymentService.buyGems);
router.post("/renewalsubscription", authentication, paymentService.AutoRenewal);

module.exports = router;
