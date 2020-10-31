let mongoose = require("mongoose");
let Schema = mongoose.Schema;

let HashtagsSchema = new Schema({
  topic: {
    type: String,
    required: true
  },
  total: {
    type: Number,
    required: true,
    default: 0
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

module.exports = mongoose.model("Hashtags", HashtagsSchema);
