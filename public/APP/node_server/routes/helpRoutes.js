const express = require("express");
const router = express.Router();
let authentication = require("../middleware/authMiddleware");

/* services */
let service = require("../services/helpService");

/* routes */
router.get("/loginterms", service.Terms);
router.get("/allterms", service.AllTerms);

module.exports = router;
