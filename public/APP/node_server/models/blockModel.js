let mongoose = require("mongoose");
let Schema = mongoose.Schema;

let blockSchema = new Schema({
  user_id: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  blocked_user: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  blocked_at: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model("Block", blockSchema);
