let mongoose = require("mongoose");
let Schema = mongoose.Schema;

let FollowingsSchema = new Schema({
  user_id: {
    type: Schema.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  follower_id: {
    type: Schema.Types.ObjectId,
    ref: "Accounts",
    required: true
  },
  mutual_follow: {
    type: Number,
    default : 0
  },
  followed_at: {
    type: Date,
    default: Date.now
  }
});

module.exports = mongoose.model("Followings", FollowingsSchema);
