const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let VideochatsSchema = new Schema({
  user_id: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  partner_id: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  chat_init_on: {
    type: Date,
    default: Date.now
  },
  chat_recent_on: {
    type: Date
  }
});

module.exports = mongoose.model("Videochats", VideochatsSchema);
