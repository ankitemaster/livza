let mongoose = require("mongoose");
let Schema = mongoose.Schema;
let SubscriptionsSchema = new Schema({
  subs_title: {
    type: String,
    required: true
  },
  subs_gems: {
    type: Number,
    required: true
  }, // gems per month
  subs_price: {
    type: Number,
    required: true
  }, // price per month
  subs_validity: {
    type: String,
    enum: ["1M", "3M", "6M", "1Y"],
    required: true
  }, // subscription validity
  purchase_count: {
    type: Number,
    default: 0
  },
  platform: {
    type: String,
    enum: ["android", "ios"],
    default: "android"
  },
  created_at: {
    type: Date,
    default: Date.now
  },
  updated_at: {
    type: Date
  }
});

module.exports = mongoose.model("Subscription", SubscriptionsSchema);
