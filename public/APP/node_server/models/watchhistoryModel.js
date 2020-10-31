let mongoose = require("mongoose");
let Schema = mongoose.Schema;

let WatchedSchema = new Schema({
  stream_id: {
    type: mongoose.Types.ObjectId,
    ref: "Streams",
    required: true
  },

  user_id: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },

  publisher: {
    type: mongoose.Types.ObjectId,
    ref: "Accounts",
    required: true
  },

  created_at: {
    type: Date,
    default: Date.now
  },

  updated_at: {
    type: Date,
    default: Date.now
  }

});

module.exports = mongoose.model("Watchhistory", WatchedSchema);



