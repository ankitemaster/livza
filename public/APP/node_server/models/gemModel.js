let mongoose = require("mongoose");
let Schema = mongoose.Schema;
let GemsSchema = new Schema({
  gem_title: {
    type: String,
    required: true
  },
  gem_icon: {
    type: String,
    required: true
  },
  gem_count: {
    type: Number,
    required: true
  },
  gem_price: {
    type: Number,
    required: true
  },
  created_at: {
    type: Date
  },
  updated_at: {
    type: Date
  },
  platform: {
    type: String,
    enum: ["android", "ios"],
    default: "android"
  },
  purchase_count: {
    type: Number,
    default: 0
  }
});

module.exports = mongoose.model("Gems", GemsSchema);
