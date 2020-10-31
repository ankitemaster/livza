const jwt = require("jsonwebtoken");
const config = require("../config/keys");

module.exports = function(req, res, next) {
  const token = req.headers.authorization;
  if (!token) {
    return res.json({
      status: "false",
      message: "Unauthorized Access"
    });
  }

  try {
    const decoded = jwt.verify(token, config.JWT_SECRET, {});
    req.user = decoded;
    next();
  } catch (error) {
    return res.json({
      status: "false",
      message: "Unauthorized Access"
    });
  }
};
