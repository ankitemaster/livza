const mongoose = require("mongoose");
const Schema = mongoose.Schema;

let adminMessagesSchema = new Schema({
  msg_type: {
    type: String,
    enum: ["text", "image"],
    default: "text"
  },
  msg_from: {
    type: String,
    enum: ["user", "admin"],
    default: "admin"
  },
  msg_to: {
    type: String,
    required: true
  },
  msg_platform: {
    type: String,
    enum: ["android", "ios"],
    default: "android"
  },
  msg_data: {
    type: String,
    required: true
  },
  msg_at: {
    type: Number,
    required: true
  },
  msg_on: {
    type: Date,
    required: true
  },
  created_at: {
    type: Date,
    required: true
  }
});

module.exports = mongoose.model("Adminchats", adminMessagesSchema);
